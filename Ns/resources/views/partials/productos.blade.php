<h2 class="text-3xl font-semibold mb-4">Productos</h2>

<!-- Botón para abrir el modal -->
<div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
        Agregar Producto
    </button>
</div>

<!-- Tabla de productos -->
<table class="table-auto w-full bg-white shadow-md rounded-lg">
    <thead>
        <tr class="bg-gray-200">
            <th class="p-3">ID</th>
            <th class="p-3">Nombre</th>
            <th class="p-3">Descripción</th>
            <th class="p-3">Stock</th>
            <th class="p-3">Precio</th>
            <th class="p-3">Imagen</th>
            <th class="p-3">Categoría</th>
            <th class="p-3">Estado</th>
            <th class="p-3">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($productos))
            @foreach ($productos as $producto)
                <tr class="border-b">
                    <td class="p-3">{{ $producto->id }}</td>
                    <td class="p-3">{{ $producto->producto }}</td>
                    <td class="p-3">{{ $producto->descripcion }}</td>
                    <td class="p-3">{{ $producto->stock }}</td>
                    <td class="p-3">${{ $producto->precio }}</td>
                    <td class="p-3">
                        @if ($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->producto }}"
                                class="img-thumbnail" style="width: 100px; height: 100px;">
                        @else
                            Sin imagen
                        @endif
                    </td>
                    <td class="p-3">{{ $producto->categoria->categoria ?? 'Sin categoría' }}</td>
                    <td class="p-3">{{ $producto->estado->estado ?? 'Sin estado' }}</td>
                    <td class="p-3">
                        <form
                            action="{{ route('dashboard.destroy', ['section' => 'productos', 'id' => $producto->id]) }}"
                            method="POST" onsubmit="return confirm('¿Eliminar producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editProductModal" data-id="{{ $producto->id }}"
                            data-producto="{{ $producto->producto }}" data-descripcion="{{ $producto->descripcion }}"
                            data-stock="{{ $producto->stock }}" data-precio="{{ $producto->precio }}"
                            data-categoria="{{ $producto->id_categoria }}" data-estado="{{ $producto->id_estado }}"
                            data-url="{{ route('dashboard.update', ['section' => 'productos', 'id' => $producto->id]) }}">
                            Editar
                        </button>

                    </td>
                </tr>
            @endforeach
        @else
            <p>No hay productos disponibles</p>
        @endif
    </tbody>
</table>

<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="edit_producto" class="form-label">Nombre del Producto</label>
                        <input type="text" name="producto" id="edit_producto" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_stock" class="form-label">Stock</label>
                        <input type="number" name="stock" id="edit_stock" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_precio" class="form-label">Precio</label>
                        <input type="number" name="precio" id="edit_precio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_imagen" class="form-label">Imagen (opcional)</label>
                        <input type="file" name="imagen" id="edit_imagen" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_categoria" class="form-label">Categoría</label>
                        <select name="id_categoria" id="edit_id_categoria" class="form-select" required>
                            <option value="" disabled>Seleccionar categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_estado" class="form-label">Estado</label>
                        <select name="id_estado" id="edit_id_estado" class="form-select" required>
                            <option value="" disabled>Seleccionar estado</option>
                            @foreach ($estados as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->estado }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar un nuevo producto -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Agregar Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dashboard.store', ['section' => 'productos']) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="producto" class="form-label">Nombre del Producto</label>
                        <input type="text" name="producto" id="producto" class="form-control"
                            placeholder="Nombre del producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción del producto"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control"
                            placeholder="Cantidad en stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" name="precio" id="precio" class="form-control"
                            placeholder="Precio del producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="id_categoria" class="form-label">Categoría</label>
                        <select name="id_categoria" id="id_categoria" class="form-select" required>
                            <option value="" disabled selected>Seleccionar categoría</option>
                            @if (isset($categorias))
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                                @endforeach
                            @else
                                <p>No hay categorías disponibles</p>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_estado" class="form-label">Estado</label>
                        <select name="id_estado" id="id_estado" class="form-select" required>
                            <option value="" disabled selected>Seleccionar estado</option>
                            @if (isset($estados))
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}">{{ $estado->estado }}</option>
                                @endforeach
                            @else
                                <p>No hay estados disponibles</p>
                            @endif
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agregar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editModal = document.getElementById('editProductModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var producto = button.getAttribute('data-producto');
            var descripcion = button.getAttribute('data-descripcion');
            var stock = button.getAttribute('data-stock');
            var precio = button.getAttribute('data-precio');
            var categoria = button.getAttribute('data-categoria');
            var estado = button.getAttribute('data-estado');

            document.getElementById('edit_producto').value = producto;
            document.getElementById('edit_descripcion').value = descripcion;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('edit_precio').value = precio;
            document.getElementById('edit_id_categoria').value = categoria;
            document.getElementById('edit_id_estado').value = estado;

            // Cambia el action del formulario para que apunte al producto correcto
            var form = document.getElementById('editProductForm');
            form.action = button.getAttribute('data-url');

        });
    });
</script>
