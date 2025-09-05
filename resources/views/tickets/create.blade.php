<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crear Ticket - Panel de Control</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Remixicon -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}" />

  <style>
    body {
      background: linear-gradient(to bottom right, #f0f9ff, #e0e7ff);
    }
    .card-custom {
      background-color: #ffffff;
      border-radius: 1rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .btn-back {
      background-color: #6b7280;
      color: white;
    }
    .btn-back:hover {
      background-color: #4b5563;
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

  <div class="w-full max-w-2xl">

    <!-- Botón Volver -->
    <div class="mb-4">
      <a href="{{ route('tickets.index') }}"
         class="btn btn-back px-4 py-2 rounded-lg inline-flex items-center gap-2 shadow hover:opacity-90 transition">
        <i class="ri-arrow-left-line"></i>
        Volver al Panel
      </a>
    </div>

    <!-- Tarjeta del formulario -->
    <div class="card-custom p-8">

      <!-- Encabezado -->
      <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800 flex items-center justify-center gap-3">
          <i class="ri-add-circle-line text-3xl text-indigo-600"></i>
          Crear Nuevo Ticket
        </h2>
        <p class="text-gray-600 mt-2">Por favor completa la información para enviar tu solicitud.</p>
      </div>

      <!-- Errores de validación -->
      @if ($errors->any())
        <div class="alert alert-danger mb-6">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Formulario -->
      <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Título -->
        <div>
          <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Título del Ticket</label>
          <input type="text" name="title" id="title"
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                 value="{{ old('title') }}"
                 placeholder="Ej: Error en facturación #4532" required />
        </div>

        <!-- Descripción -->
        <div>
          <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Descripción Detallada</label>
          <textarea name="description" id="description" rows="5"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Describe claramente el problema..." required>{{ old('description') }}</textarea>
        </div>

        <!-- Categoría -->
        @if(isset($categories) && count($categories))
          <div>
            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1">Categoría</label>
            <select name="category_id" id="category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
              <option value="">Seleccionar categoría...</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id')==$category->id?'selected':'' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
        @endif

        <!-- Prioridad -->
        <div>
          <label for="priority" class="block text-sm font-semibold text-gray-700 mb-1">Prioridad</label>
          <select name="priority" id="priority"
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="low"  {{ old('priority')=='low'?'selected':'' }}>Baja</option>
            <option value="medium" {{ old('priority','medium')=='medium'?'selected':'' }}>Media</option>
            <option value="high" {{ old('priority')=='high'?'selected':'' }}>Alta</option>
            <option value="urgent" {{ old('priority')=='urgent'?'selected':'' }}>Urgente</option>
          </select>
        </div>

        <!-- Botón Submit -->
        <div class="text-center">
          <button type="submit"
                  class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-lg shadow transition">
            <i class="ri-send-plane-fill me-2"></i>
            Enviar Ticket
          </button>
        </div>

      </form>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
