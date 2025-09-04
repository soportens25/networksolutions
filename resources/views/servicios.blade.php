<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Network Solutions - {{ $servicio->servicio }}</title>
  <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans">

  <!-- Navbar -->
  <nav class="bg-gray-900 text-white flex justify-between items-center px-6 py-4 shadow-md sticky top-0 z-50">
    <a href="{{ asset('/') }}" class="text-2xl font-bold text-orange-500 hover:text-orange-600 transition">Network Solutions</a>
    <div>
      @if (auth()->check())
        <p class="text-white font-medium">{{ auth()->user()->name }}</p>
      @else
        <span class="text-gray-400 italic">Invitado</span>
      @endif
    </div>
  </nav>

  <main class="max-w-7xl mx-auto p-6">

    <!-- Sección Superior: Imagen y Texto -->
    <section class="mb-16 text-center md:text-left">
      <div class="mb-8 max-w-4xl mx-auto">
        @if ($servicio->imagen)
          <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="Imagen Principal" 
               class="w-[500px] max-h-[500px] object-cover rounded-lg shadow-lg mx-auto" />
        @endif
      </div>
      <div class="max-w-3xl mx-auto px-4 md:px-0">
        <h1 class="text-4xl font-extrabold text-orange-500 mb-4">{{ $servicio->servicio }}</h1>
        <p class="text-lg font-semibold text-gray-700 mb-6">{!! $servicio->tipo !!}</p>
        @if ($servicio->especificacion)
          <div class="bg-gray-50 rounded-lg p-6 shadow-inner text-justify leading-relaxed text-gray-600 whitespace-pre-line">
            {!! nl2br(e($servicio->especificacion)) !!}
          </div>
        @endif
      </div>
    </section>

    <!-- Sección Sitios Estratégicos -->
    <section class="bg-gray-100 rounded-xl p-8 shadow-md">
      <h2 class="text-3xl font-bold text-gray-900 uppercase tracking-wide mb-8 text-center">Nuestros Pilares</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @if ($servicio->imagen1)
          <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow p-5 flex flex-col">
            <img src="{{ asset('storage/' . $servicio->imagen1) }}" alt="Personal calificado" class="rounded-md mb-4 object-cover h-40 w-full" />
            <h3 class="text-xl font-semibold text-orange-500 mb-2">Personal Calificado</h3>
            <p class="text-gray-700 flex-grow">Contamos con el mejor personal con el conocimiento y experiencia para brindarle la mejor experiencia a los clientes.</p>
          </div>
        @endif
        @if ($servicio->imagen2)
          <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow p-5 flex flex-col">
            <img src="{{ asset('storage/' . $servicio->imagen2) }}" alt="El mejor conocimiento" class="rounded-md mb-4 object-cover h-40 w-full" />
            <h3 class="text-xl font-semibold text-orange-500 mb-2">El Mejor Conocimiento</h3>
            <p class="text-gray-700 flex-grow">Contamos con la experiencia para brindar las mejores soluciones a los clientes.</p>
          </div>
        @endif
        @if ($servicio->imagen3)
          <a href="{{ asset('/') }}#products" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow p-5 flex flex-col no-underline cursor-pointer">
            <img src="{{ asset('storage/' . $servicio->imagen3) }}" alt="Productos de alta calidad" class="rounded-md mb-4 object-cover h-40 w-full" />
            <h3 class="text-xl font-semibold text-orange-500 mb-2">Productos de Alta Calidad</h3>
            <p class="text-gray-700 flex-grow">Contamos con productos de alta calidad para brindar las mejores soluciones a los clientes.</p>
          </a>
        @endif
      </div>
    </section>

  </main>

  <footer class="bg-gray-900 text-white text-center py-6 mt-16">
    <p>&copy; 2025 <a href="#" class="text-orange-500 hover:underline">Network Solutions</a>. Todos los derechos reservados.</p>
  </footer>

</body>

</html>
