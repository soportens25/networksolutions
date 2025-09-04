        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>{{ $categoria->categoria }} - Catálogo de Productos</title>
            <link rel="icon" href="{{ asset('image/logo.jpg') }}" />
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
            <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
            <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background-color: #fefefe;
                    color: #212529;
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                }

                main {
                    flex-grow: 1;
                    padding: 2rem 1rem;
                    max-width: 1200px;
                    margin: 0 auto;
                    width: 100%;
                }

                .navbar-custom {
                    background-color: #343a40;
                }

                .navbar-brand,
                .navbar-text {
                    color: #fff !important;
                }

                .navbar-text {
                    font-weight: 500;
                }

                /* Cards */
                .product-card {
                    border: none;
                    box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
                    border-radius: 12px;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                    max-width: 320px;
                    margin: 0 auto 2.5rem;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }

                .product-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 10px 25px rgb(0 0 0 / 0.15);
                }

                .product-image {
                    border-top-left-radius: 12px;
                    border-top-right-radius: 12px;
                    height: 300px;
                    object-fit: cover;
                }

                .card-body {
                    text-align: center;
                    padding: 1.5rem;
                    flex-grow: 1;
                }

                .card-title {
                    font-size: 1.4rem;
                    font-weight: 700;
                    margin-bottom: 0.5rem;
                    color: #ff6600;
                }

                .price-text {
                    font-size: 1.3rem;
                    font-weight: 600;
                    color: #28a745;
                    margin-bottom: 0.75rem;
                }

                .stock-text {
                    font-size: 1rem;
                    color: #6c757d;
                    margin-bottom: 1rem;
                }

                .card-text {
                    font-size: 1rem;
                    color: #495057;
                    margin-bottom: 1.5rem;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    /* Limita la descripción a 3 líneas */
                    -webkit-box-orient: vertical;
                }

                .card-footer {
                    background: transparent;
                    border-top: none;
                    text-align: center;
                    padding-bottom: 1.5rem;
                }

                .btn-primary {
                    background-color: #ff6600;
                    border-color: #ff6600;
                    padding: 0.6rem 1.5rem;
                    font-weight: 600;
                    border-radius: 30px;
                    transition: background-color 0.3s ease;
                }

                .btn-primary:hover {
                    background-color: #e65500;
                    border-color: #e65500;
                }

                .btn-warning {
                    border-radius: 30px;
                    font-weight: 600;
                }

                /* Responsive tweaks */
                @media (max-width: 576px) {
                    .product-card {
                        max-width: 100%;
                        margin-bottom: 2rem;
                    }

                    .product-image {
                        height: 180px;
                    }
                }

                /* Modal custom */
                #productModal .modal-body {
                    max-height: 60vh;
                    overflow-y: auto;
                }

                #modalProductImage {
                    border-radius: 12px;
                    max-height: 300px;
                    object-fit: contain;
                    width: 100%;
                }

                #modalProductDescription {
                    white-space: pre-wrap;
                }
            </style>
        </head>

        <body>
            <header>
                <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
                    <div class="container">
                        <a href="javascript:history.back()" class="navbar-brand d-flex align-items-center">
                            <i class="ri-arrow-go-back-fill fs-4 me-2"></i> Volver
                        </a>

                        <span class="navbar-text mx-auto fw-bold fs-4">{{ $categoria->categoria }}</span>

                        <div>
                            @if (auth()->check())
                                <p class="text-white mb-0 fw-semibold">
                                    {{ auth()->user()->name }}
                                </p>
                            @else
                                <span class="navbar-text">Invitado</span>
                            @endif
                        </div>
                    </div>
                </nav>
            </header>

            <main>
                <h1 class="text-center mb-5 fw-bold">Catálogo de Productos</h1>

                @if ($productos->isNotEmpty())
                    <div class="d-flex flex-wrap justify-content-center gap-4">
                        @foreach ($productos as $producto)
                            <div class="product-card">
                                @if ($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}"
                                        class="product-image" />
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $producto->producto }}</h5>
                                    <p class="price-text">$ {{ number_format($producto->precio, 0, ',', '.') }}</p>
                                    <p class="stock-text">Disponibilidad: {{ $producto->stock }} unidades</p>
                                    <p class="card-text">{{ $producto->descripcion }}</p>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-outline-primary w-100 mt-3" data-bs-toggle="modal"
                                        data-bs-target="#productModal" data-name="{{ $producto->producto }}"
                                        data-price="{{ number_format($producto->precio, 0, ',', '.') }}"
                                        data-stock="{{ $producto->stock }}" data-description="{{ $producto->descripcion }}"
                                        data-image="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : '' }}"
                                        data-link="@auth https://wa.me/573182927165?text=¡Hola!, quisiera consultar más sobre el producto '{{ $producto->producto }}', quedo atento gracias. @else {{ route('login') }} @endauth">
                                        Ver detalle
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center mt-5">
                        <h2 class="text-secondary">No hay productos disponibles en esta categoría.</h2>
                    </div>
                @endif
                <!-- Modal -->
                <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable ">
                        <div class="modal-content rounded-4 shadow-lg">
                            <div class="modal-header border-0 bg-gray-100 shadow">
                                <h5 class="modal-title fw-semibold" id="productModalLabel">Nombre del Producto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Contenido dinámico del producto -->
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <img src="" alt="" id="modalProductImage" class="img-fluid rounded shadow" />
                                    </div>
                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                        <p class="price-text fs-4 text-success fw-bold">$ Precio</p>
                                        <p class="stock-text">Disponibilidad: X unidades</p>
                                        <p id="modalProductDescription" class="mb-0"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                @auth
                                    <a href="https://wa.me/573182927165?text=¡Hola!, quisiera consultar más sobre el producto '{{ $producto->producto }}', quedo atento gracias."
                                        target="_blank" class="btn btn-primary mt-2">
                                        Ir a comprar
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn bg-danger mt-2">
                                        Inicia sesión para comprar
                                    </a>
                                @endauth

                                <button type="button" class="btn btn-secondary px-4 py-2 fs-6 fw-semibold"
                                    data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

            <footer class="bg-dark text-white py-3 text-center mt-auto">
                <p class="mb-0">© 2025 Network Solutions. Todos los derechos reservados.</p>
            </footer>

            <!-- Bootstrap JS (Popper + Bootstrap) -->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

            <script>
                var productModal = document.getElementById('productModal');
                productModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;

                    // Obtener datos
                    var name = button.getAttribute('data-name');
                    var price = button.getAttribute('data-price');
                    var stock = button.getAttribute('data-stock');
                    var description = button.getAttribute('data-description');
                    var image = button.getAttribute('data-image');
                    var link = button.getAttribute('data-link');

                    // Actualizar modal
                    productModal.querySelector('.modal-title').textContent = name;
                    productModal.querySelector('.price-text').textContent = '$ ' + price;
                    productModal.querySelector('.stock-text').textContent = 'Disponibilidad: ' + stock + ' unidades';
                    productModal.querySelector('#modalProductDescription').textContent = description;

                    var imgElem = productModal.querySelector('#modalProductImage');
                    if (image) {
                        imgElem.src = image;
                        imgElem.alt = name;
                        imgElem.style.display = 'block';
                    } else {
                        imgElem.style.display = 'none';
                    }

                    var buyLink = productModal.querySelector('#modalBuyLink');
                    buyLink.href = link;
                });
            </script>
        </body>

        </html>
