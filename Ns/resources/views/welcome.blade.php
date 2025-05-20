@php
    use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NETWORK SOLUTIONS IT PBX</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="website icon" href="{{ asset('storage/image/logo.jpg') }}">
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

        .swiper-container {
            overflow: hidden;
            padding-bottom: 60px;
            /* Espacio para la paginación */
        }

        /* Centrar correctamente la paginación */
        .swiper-pagination {
            position: relative !important;
            bottom: 0 !important;
            text-align: center;
        }

        /* Mejorar visibilidad de los botones de navegación */
        .swiper-button-next,
        .swiper-button-prev {
            color: #f97316;
            /* Naranja */
        }

        /* Evitar que los botones de navegación tapen contenido */
        .swiper-button-next {
            right: -10px;
            /* Ajuste lateral */
        }

        .swiper-button-prev {
            left: -10px;
            /* Ajuste lateral */
        }
    </style>
</head>

<body class="bg-white text-gray-800">

    <header class="fixed top-0 left-0 w-full z-40 bg-black/70 backdrop-blur-sm text-white py-3 shadow-md">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="text-2xl font-bold">Network Solutions <p class="text-sm text-gray-400 font-normal"> <i
                        class="ri-calendar-check-line"></i> Horarios de atencion: 7:00 AM a 6:00PM</p> <a
                    href="tel:3182927165" class="text-sm text-gray-400 font-normal"><i class="ri-phone-line"></i>
                    Contacto: 318 292 7165 - </a> <a href="tel:3204563641" class="text-sm text-gray-400 font-normal"><i
                        class="ri-whatsapp-line"></i> Contacto: 320 456 3641</a> </h1>
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
                <a href="{{ route('login') }}" class="hover:underline text-orange-400 font-bold">Iniciar sesión</a>
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
        <div class="relative z-10 bg-black/50 py-52 w ">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold text-white">Conectando tu mundo con tecnología innovadora</h2>
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
                            <div class="swiper-slide flex justify-center">
                                <div class="service-card">
                                    <a href="{{ route('servicio', $servicio->id) }}"
                                        class="block bg-white rounded-xl shadow-md p-6 text-center transform transition duration-300 hover:scale-105 hover:shadow-xl">

                                        @if ($servicio->imagen)
                                            <div class="flex justify-center mb-4">
                                                <img src="{{ asset('storage/' . $servicio->imagen) }}"
                                                    alt="{{ $servicio->servicio }}"
                                                    class="w-24 h-24 object-cover rounded-full border-2 border-orange-500 shadow-md">
                                            </div>
                                        @endif

                                        <h3 class="text-xl font-semibold text-gray-800">{{ $servicio->servicio }}</h3>
                                        <p class="text-sm text-gray-600 mt-2 leading-relaxed">{!! $servicio->tipo !!}</p>
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

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" style="height: 500px;" src=" {{ asset('storage/image/imagen_c1.jpg') }}"
                    alt="First slide" style="object-fit: cover; object-position: center;">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" style="height: 500px;" src="{{ asset('storage/image/imagen_c2.webp') }}"
                    alt="Second slide" style="object-fit: cover; object-position: center;">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" style="height: 500px;" src=" {{ asset('storage/image/imagen_c3.jpg') }}"
                    alt="Third slide" style="object-fit: cover; object-position: center;">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" style="height: 500px;" src="{{ asset('storage/image/imagen_c4.webp') }}"
                    alt="Fourth slide" style="object-fit: cover; object-position: center;">
            </div>
        </div>
        <!-- Controles de Navegación -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
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
    <a href="https://wa.me/+573114020692?text=¡Hola!, quisiera saber más" target="_blank" class="whatsapp-bubble">
        <i class="bi bi-whatsapp whatsapp-icon text-white text-2xl"></i>
    </a>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-8">
        <div
            class="container mx-auto flex flex-col md:flex-row items-center justify-center space-y-8 md:space-y-0 px-4">
            <!-- Sección Redes Sociales -->
            <div class="flex space-x-6 justify-center">
                <a href="https://www.facebook.com" target="_blank"
                    class="transform transition-transform duration-300 hover:scale-110 hover:text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-10 h-10"
                        viewBox="0 0 24 24">
                        <path
                            d="M22,12A10,10,0,1,0,12,22V14H9v-2h3V9.5A3.5,3.5,0,0,1,15.5,6H18V8h-2.5A1.5,1.5,0,0,0,14,9.5V12h4l-1,2H14v8A10,10,0,0,0,22,12Z" />
                    </svg>
                </a>
                <a href="https://www.twitter.com" target="_blank"
                    class="transform transition-transform duration-300 hover:scale-110 hover:text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-10 h-10"
                        viewBox="0 0 24 24">
                        <path
                            d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z" />
                    </svg>
                </a>
                <a href="https://www.instagram.com" target="_blank"
                    class="transform transition-transform duration-300 hover:scale-110 hover:text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-10 h-10"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.333 3.608 1.308.975.975 1.246 2.242 1.308 3.608.058 1.266.069 1.646.069 4.85s-.012 3.584-.069 4.85c-.062 1.366-.333 2.633-1.308 3.608-.975.975-2.242 1.246-3.608 1.308-1.266.058-1.646.069-4.85.069s-3.584-.012-4.85-.069c-1.366-.062-2.633-.333-3.608-1.308-.975-.975-1.246-2.242-1.308-3.608C2.175 15.746 2.163 15.366 2.163 12s.012-3.584.069-4.85c.062-1.366.333-2.633 1.308-3.608.975-.975 2.242-1.246 3.608-1.308C8.416 2.175 8.796 2.163 12 2.163m0-2.163C8.741 0 8.332.01 7.052.07c-1.411.064-2.997.338-4.1 1.442C1.85 2.627 1.576 4.213 1.512 5.624.926 8.332 0 8.741 0 12s.926 3.668 1.512 6.376c.064 1.411.338 2.997 1.442 4.1 1.103 1.104 2.689 1.378 4.1 1.442C8.332 23.99 8.741 24 12 24s3.668-.01 6.376-.07c1.411-.064 2.997-.338 4.1-1.442 1.104-1.103 1.378-2.689 1.442-4.1C23.99 15.668 24 15.259 24 12s-.01-3.668-.07-6.376c-.064-1.411-.338-2.997-1.442-4.1C20.373 1.85 18.787 1.576 17.376 1.512 15.668.926 15.259 0 12 0z" />
                        <circle cx="12" cy="12" r="3.2" />
                        <path d="M18.406 5.594a1.44 1.44 0 1 1-2.876 0 1.44 1.44 0 0 1 2.876 0z" />
                    </svg>
                </a>
            </div>

            <!-- Sección de Información -->
            <div class="text-center mt-8 md:mt-0">
                <h2 class="text-xl font-bold mb-2 text-orange-500">Contacto</h2>
                <p>Email: <a href="mailto:rvelasco@gmail.com"
                        class="hover:text-orange-500 transition duration-300">soporte@nsitpbx.com</a></p>
                <p>Teléfono: 318 292 7165</p>
                <p class="mt-4 text-gray-500">&copy; 2024 Network Solutions. Todos los derechos reservados.</p>
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
                spaceBetween: 10,
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
