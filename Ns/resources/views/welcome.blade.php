    @php
        use Illuminate\Support\Str;
    @endphp
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Network Solutions IT PBX</title>
        <meta name="description"
            content="Soluciones profesionales en telecomunicaciones, telefonía IP y redes empresariales." />
        <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}">
        <link rel="website icon" href="{{ asset('storage/image/logo.jpg') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" rel="stylesheet">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            /* Estilos personalizados */
            .whatsapp-bubble {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background-color: #25d366;
                border-radius: 50%;
                padding: 10px 15px;
                z-index: 999;
                transition: transform 0.3s ease-in-out;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }

            .whatsapp-bubble:hover {
                transform: translateY(-5px);
            }

            .whatsapp-icon {
                width: 50px;
                height: 50px;
            }
        </style>
    </head>

    <body class="bg-gray-200 text-gray-800">

        <header class="fixed top-0 left-0 w-full z-40 bg-black/70 backdrop-blur-sm text-white py-3 shadow-md">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="d-flex">
                    <img style="    height: 5rem;
        margin: auto;     margin-right: 10px;
        border-radius: 0.6rem;
    "
                        src="{{ asset('storage/image/logotipo_ns.png') }}" alt="">
                    <h1 class="text-2xl font-bold">Network Solutions <br><a href="tel:3182927165"
                            class="text-sm text-gray-400 font-normal"><i class="ri-phone-line"></i>
                            Contacto: 318 292 7165 - </a> <a href="tel:3204563641"
                            class="text-sm text-gray-400 font-normal"><i class="ri-whatsapp-line"></i> Contacto: 320 456
                            3641</a></h1>
                </div>
                @if (auth()->check())
                    <div class="user-profile d-flex align-items-center">
                        <div class="px-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </div>
                        <div>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle bg-transparent border-0 text-white"
                                    type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ auth()->user()->name }}
                                </button>
                                <ul style="background-color: gray; color: #bfc0c2;" class="dropdown-menu"
                                    aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item text-stone-300" href="{{ route('profile.update') }}">Ver
                                            perfil</a></li>
                                    @if (auth()->check() && auth()->user()->hasRole('admin|empresarial'))
                                        <li>
                                            <a href="{{ route('dashboard') }}" class="dropdown-item text-stone-300">Panel de
                                                control</a>
                                        </li>
                                    @endif
                                    @if (auth()->check() && auth()->user()->hasRole('admin|empresarial|tecnico'))
                                        <li>
                                            <a href="{{ route('tickets.index') }}" class="dropdown-item text-stone-300">Help
                                                Desk</a>
                                        </li>
                                    @endif

                                    <li>
                                        <form style="margin-left:16px;" method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit">Cerrar sesión</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hover:underline text-orange-400 font-bold"><i
                            class="ri-user-3-line"></i> Iniciar sesión</a>
                @endif
            </div>
        </header>

        <section class="text-gray-800 text-center  relative overflow-hidden">
            <!-- Video de fondo -->
            <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover">
                <source src="{{ asset('storage/image/Video.mp4') }}" type="video/mp4">
                Tu navegador no soporta la reproducción de videos.
            </video>

            <!-- Contenido del texto -->
            <div class="relative z-10 bg-black/50 py-52">
                <div class="container mx-auto">
                    <h2 class="text-4xl font-semibold text-white">Conectando tu mundo <br> con tecnología innovadora</h2>
                    <p class="text-lg mb-6 text-gray-200">Descubre nuestros servicios de telecomunicaciones y los mejores
                        dispositivos
                        tecnológicos.</p>
                </div>
            </div>
        </section>

        <section id="services" class="py-12 bg-gray-50">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center mb-8 text-orange-500">Nuestros Servicios</h2>

                @if ($servicios->isNotEmpty())
                    <!-- Contenedor del slider -->
                    <div class="swiper-container relative">
                        <div class="swiper-wrapper">
                            @foreach ($servicios as $servicio)
                                <div class="swiper-slide flex justify-center items-stretch">
                                    <div class="service-card h-full">
                                        <a href="{{ route('servicio', $servicio->id) }}"
                                            class="block bg-white rounded-xl shadow-md p-6 text-center h-full min-h-[20rem] transform transition duration-300 hover:scale-105 hover:shadow-xl">

                                            @if ($servicio->imagen)
                                                <div class="flex justify-center mb-4">
                                                    <img src="{{ asset('storage/' . $servicio->imagen) }}"
                                                        alt="{{ $servicio->servicio }}"
                                                        class="w-24 h-24 object-cover rounded-full border-2 border-orange-500 shadow-md">
                                                </div>
                                            @endif

                                            <h3 class="text-xl font-semibold text-gray-800">{{ $servicio->servicio }}</h3>
                                            <p class="text-sm text-gray-600 mt-2 leading-relaxed line-clamp-3">
                                                {!! $servicio->tipo !!}
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <!-- Paginación correctamente posicionada -->
                        <div class="swiper-pagination mt-6"></div>

                        <!-- Botones de navegación -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                @else
                    <div class="bg-white p-6 text-center shadow-md rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-700">
                            No hay servicios disponibles en este momento.
                        </h3>
                    </div>
                @endif
            </div>
        </section>

        <div x-data="{ active: 0, slides: 4 }"
            class="relative w-full max-w-5xl mx-auto mt-16 mb-16 rounded-2xl shadow-xl overflow-hidden">
            <!-- Slides -->
            <div class="flex transition-transform duration-700 ease-in-out"
                :style="'transform: translateX(-' + (active * 100) + '%)'">
                <template
                    x-for="(img, index) in [
                    '{{ asset('storage/image/imagen_c1.jpg') }}',
                    '{{ asset('storage/image/imagen_c2.webp') }}',
                    '{{ asset('storage/image/imagen_c3.jpg') }}',
                    '{{ asset('storage/image/imagen_c4.webp') }}'
                ]">
                    <div class="min-w-full relative">
                        <img :src="img" class="w-full h-[28rem] object-cover" alt="Imagen del slider" />
                    </div>
                </template>
            </div>

            <!-- Indicadores -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3 z-10">
                <template x-for="i in slides">
                    <button @click="active = i - 1" class="w-4 h-4 rounded-full border border-white"
                        :class="active === i - 1 ? 'bg-white' : 'bg-white/30 hover:bg-white/50 transition'">
                    </button>
                </template>
            </div>

            <!-- Controles -->
            <button
                class="absolute top-1/2 left-4 -translate-y-1/2 text-white bg-black/40 hover:bg-black/60 rounded-full w-10 h-10 flex items-center justify-center transition z-10"
                @click="active = (active - 1 + slides) % slides" aria-label="Anterior">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                class="absolute top-1/2 right-4 -translate-y-1/2 text-white bg-black/40 hover:bg-black/60 rounded-full w-10 h-10 flex items-center justify-center transition z-10"
                @click="active = (active + 1) % slides" aria-label="Siguiente">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <section id="products" class="py-12 bg-gray-100">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center mb-8 text-orange-500">Nuestros Productos</h2>

                @if ($categorias->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($categorias as $categoria)
                            <a href="{{ route('productos_por_categoria', $categoria->id) }}"
                                class="border border-gray-300 rounded-xl bg-white shadow-md p-6 text-center transform transition duration-300 hover:scale-105 hover:shadow-lg">

                                @if ($categoria->imagen)
                                    <div class="flex justify-center">
                                        <img src="{{ asset('storage/' . $categoria->imagen) }}"
                                            alt="{{ $categoria->categoria }}"
                                            class="w-32 h-32 object-cover rounded-lg border-2 border-orange-500 shadow-md">
                                    </div>
                                @endif

                                <h3 class="text-xl font-semibold text-gray-800 mt-4">{{ $categoria->categoria }}</h3>
                                <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                                    {{ Str::words($categoria->explicacion, 15) }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white p-6 text-center shadow-md rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-700">No hay categorías disponibles en este momento.</h3>
                    </div>
                @endif
            </div>
        </section>

        <section id="marcas_aliadas" class="py-12 bg-gray-50">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center mb-8 text-orange-500">Nuestras Marcas Aliadas</h2>
                <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 border shadow-md rounded-xl p-6 bg-white">
                    @foreach (['fortinet.svg', 'Hikvision.png', 'Yeastar.png', 'fanvil.avif', 'grandstream.png', 'hilook.png', 'jabra.png', 'logitech.png', 'MikroTik.jpg', 'nexxt.png', 'Poly.png', 'tp-link.png', 'ubiquiti.png', 'vt-head.png', 'wacom.webp', 'yealink.png'] as $logo)
                        <div class="flex items-center justify-center p-4">
                            <img src="{{ asset('storage/marcas-aliadas/' . $logo) }}"
                                alt="{{ pathinfo($logo, PATHINFO_FILENAME) }}"
                                class="max-w-[150px] mx-auto hover:scale-110 transition-transform duration-300">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- WhatsApp Bubble -->
        <!-- Alpine.js debe estar cargado en el <head> -->
        <div x-data="{ showMessage: false }" x-init="setTimeout(() => showMessage = true, 1500)" class="fixed bottom-20 right-5 z-50">
            <!-- Mensaje flotante -->
            <div x-show="showMessage" x-transition
                class="bg-white text-gray-800 text-sm px-4 py-2 rounded-lg shadow-lg mb-2 max-w-xs">
                <p>¡Hola! ¿En qué podemos ayudarte hoy?</p>
            </div>

            <!-- Burbuja de WhatsApp -->
            <a href="https://wa.me/573114020692?text=¡Hola!, quisiera saber más" target="_blank"
                class="whatsapp-bubble flex items-center justify-center bg-[#25D366] rounded-full w-14 h-14 hover:scale-110 transition transform shadow-lg"
                @mouseenter="showMessage = true" @mouseleave="showMessage = false">
                <i class="ri-whatsapp-line text-white text-3xl"></i>
            </a>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-8">
            <div
                class="container mx-auto flex flex-col md:flex-row items-center justify-center space-y-8 md:space-y-0 px-4">
                <!-- Sección Redes Sociales -->
                <div class="flex space-x-6 justify-center">
                    <a href="https://www.facebook.com" target="_blank"
                        class="transform transition-transform duration-300 hover:scale-110 hover:text-orange-500">
                        <i class="ri-facebook-circle-fill text-3xl"></i>
                    </a>
                    <a href="https://www.twitter.com" target="_blank"
                        class="transform transition-transform duration-300 hover:scale-110 hover:text-orange-500">
                        <i class="ri-twitter-x-fill text-3xl"></i>
                    </a>
                    <a href="https://www.instagram.com" target="_blank"
                        class="transform transition-transform duration-300 hover:scale-110 hover:text-orange-500">
                        <i class="ri-instagram-line text-3xl"></i>
                    </a>
                </div>

                <!-- Sección de Información -->
                <div class="text-center mt-8 md:mt-0">
                    <h2 class="text-xl font-bold mb-2 text-orange-500">Contacto</h2>
                    <p>Email: <a href="mailto:rvelasco@gmail.com"
                            class="hover:text-orange-500 transition duration-300">soporte@nsitpbx.com</a></p>
                    <p>Teléfono: 318 292 7165</p>
                    <p class="mt-4 text-gray-500">&copy; 2025 Network Solutions IT PBX. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>


        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
        <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                new Swiper('.swiper-container', {
                    loop: true,
                    spaceBetween: 20,
                    slidesPerView: 1,
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                        dynamicBullets: true,
                        dynamicMainBullets: true,
                        dynamicFractionBullets: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next", // Actualizado
                        prevEl: ".swiper-button-prev", // Actualizado
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 1,
                            centeredSlides: true
                        },
                        768: {
                            slidesPerView: 2,
                            centeredSlides: true
                        },
                        1024: {
                            slidesPerView: 3
                        },
                    }
                });
            });
        </script>
    </body>

    </html>
