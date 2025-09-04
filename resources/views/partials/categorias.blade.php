        <h2 class="text-3xl font-semibold mb-4">Categorías</h2>
        <!-- resources/views/dashboard.blade.php -->
        <div class="relative mb-18">
            <button type="button" class="absolute top-0 right-0 bg-green-500 hover:bg-green-600 text-white p-2 rounded shadow" data-bs-toggle="modal"
                data-bs-target="#addCategoryModal">
                + Agregar Categoría
            </button>
        </div>

        <table class="table-auto w-full bg-white shadow-md rounded-lg mt-20">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3">ID</th>
                    <th class="p-3">Nombre</th>
                    <th class="p-3">Explicacion</th>
                    <th class="p-3">imagen</th>
                    <th class="p-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($categorias))
                    @foreach ($categorias as $categoria)
                        <tr class="border-b">
                            <td class="p-3">{{ $categoria->id }}</td>
                            <td class="p-3">{{ $categoria->categoria }}</td>
                            <td class="p-3">{{ $categoria->explicacion }}</td>
                            <td class="p-3"><img src="{{ asset('storage/' . $categoria->imagen) }}"
                                    alt="Imagen de la categoría" class="w-20 h-20 object-cover">
                            <td class="p-3">
                                <form
                                    action="{{ route('dashboard.destroy', ['section' => 'categorias', 'id' => $categoria->id]) }}"
                                    method="POST" onsubmit="return confirm('¿Eliminar categoría?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded">🗑️ Eliminar</button>
                                </form>
                                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded my-4" data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal" data-id="{{ $categoria->id }}"
                                    data-categoria="{{ $categoria->categoria }}"
                                    data-explicacion="{{ $categoria->explicacion }}"
                                    data-url="{{ route('dashboard.update', ['section' => 'categorias', 'id' => $categoria->id]) }}">
                                    📝 Editar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <p>No hay categorías disponibles</p>
                @endif
            </tbody>
        </table>
        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any() && session('modal') == 'editCategory')
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="edit_id">
                            <div class="mb-3">
                                <label for="edit_categoria" class="form-label">Nombre de la Categoría</label>
                                <input type="text" name="categoria" id="edit_categoria" class="form-control"
                                    value="{{ old('categoria') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_explicacion" class="form-label">Explicación</label>
                                <input type="text" name="explicacion" id="edit_explicacion" class="form-control"
                                    value="{{ old('explicacion') }}">
                            </div>
                            <div class="mb-3">
                                <label for="edit_imagen" class="form-label">Imagen (opcional)</label>
                                <input type="file" name="imagen" id="edit_imagen" class="form-control"
                                    accept="image/*">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Agregar Nueva Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('dashboard.store', ['section' => 'categorias']) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Nombre de la Categoría</label>
                                <input type="text" name="categoria" id="categoria" class="form-control"
                                    placeholder="Nombre de la categoría" required>
                            </div>
                            <div class="mb-3">
                                <label for="explicacion" class="form-label">Explicación</label>
                                <input type="text" name="explicacion" id="explicacion" class="form-control"
                                    placeholder="Explicación de la categoría">
                            </div>
                            <div class="mb-3">
                                <label for="imagen" class="form-label">Imagen</label>
                                <input type="file" name="imagen" id="imagen" class="form-control"
                                    accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar Categoría</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var editModal = document.getElementById('editCategoryModal');
                editModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    if (!button) return;
                    var id = button.getAttribute('data-id');
                    var categoria = button.getAttribute('data-categoria');
                    var explicacion = button.getAttribute('data-explicacion');
                    var url = button.getAttribute('data-url');

                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_categoria').value = categoria;
                    document.getElementById('edit_explicacion').value = explicacion;

                    // Cambia el action del formulario para que apunte a la ruta correcta
                    var form = document.getElementById('editCategoryForm');
                    form.action = url;
                });

                // Si hubo errores y se debe reabrir el modal (tras validación)
                @if ($errors->any() && session('modal') == 'editCategory')
                    var myModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
                    myModal.show();
                @endif
            });
        </script>
