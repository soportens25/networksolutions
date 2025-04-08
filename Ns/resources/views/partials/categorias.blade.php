    <h2 class="text-3xl font-semibold mb-4">Categorías</h2>
    <!-- resources/views/dashboard.blade.php -->
    <div class="relative mb-18">
        <button type="button" class="absolute top-0 right-0 btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#addCategoryModal">
            Agregar Categoría
        </button>
    </div>

    <table class="table-auto w-full bg-white shadow-md rounded-lg mt-20">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3">ID</th>
                <th class="p-3">Nombre</th>
                <th class="p-3">Explicacion</th>
                <th class="p-3">imagen</th>
                <th class="p-3">Explicacion</th>

                <th class="p-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($categorias))
            @foreach ($categorias as $categoria)
            <tr class="border-b">
                <td class="p-3">{{ $categoria->id }}</td>
                <td class="p-3">{{ $categoria->categoria }}</td>
                <td class="p-3">{{ $categoria->explicacion }}</td>
                <td class="p-3"><img src="{{ asset('storage/' . $categoria->imagen) }}" alt="Imagen de la categoría"
                        class="w-20 h-20 object-cover">
                <td class="p-3">
                    <form action="{{ route('dashboard.destroy', ['section' => 'categorias', 'id' => $categoria->id]) }}"
                        method="POST" onsubmit="return confirm('¿Eliminar categoría?');">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @else
            <p>No hay categorías disponibles</p>
            @endif
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Agregar Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('dashboard.store', ['section' => 'categorias']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Nombre de la Categoría</label>
                            <input type="text" name="categoria" id="categoria" class="form-control" placeholder="Nombre de la categoría" required>
                        </div>
                        <div class="mb-3">
                            <label for="explicacion" class="form-label">Explicación</label>
                            <input type="text" name="explicacion" id="explicacion" class="form-control" placeholder="Explicación de la categoría">
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Categoría</button>
                    </form>
                </div>
            </div>
        </div>
    </div>