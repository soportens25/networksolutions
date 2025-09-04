<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crear Ticket - Panel de Control</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <!-- Remixicon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}" />
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100 min-h-screen py-10">

    <!-- 🟣 Banner del apartado -->
    <div class="bg-indigo-600 text-white py-4 px-6 shadow-md rounded mx-4 md:mx-20 mb-6">
        <h1 class="text-xl font-bold flex items-center gap-2">
            <i class="ri-add-circle-line text-2xl"></i>
            Crear Nuevo Ticket
        </h1>
    </div>

    <!-- 🔧 Formulario de creación -->
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6 border border-indigo-200">
        <form action="{{ route('tickets.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="title" class="form-label fw-bold">Título del ticket</label>
                <input type="text" name="title" class="form-control" placeholder="Ej: Error en el sistema de facturación" required>
            </div>

            <div>
                <label for="description" class="form-label fw-bold">Descripción detallada</label>
                <textarea name="description" rows="5" class="form-control" placeholder="Describe el problema con la mayor claridad posible..." required></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success px-4 py-2">
                    <i class="ri-send-plane-fill me-1"></i> Enviar Ticket
                </button>
            </div>
        </form>
    </div>

</body>
</html>
