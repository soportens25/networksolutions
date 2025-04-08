<h2 class="text-3xl font-semibold mb-4">Servicios</h2>

<!-- Botón para abrir el modal de agregar servicio -->
<div class="relative mb-4">
    <button type="button" class="absolute top-0 right-0 btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
        Agregar Servicio
    </button>
</div>

<!-- Tabla de servicios -->
<table class="table-auto w-full bg-white shadow-md rounded-lg mt-20">
    <thead>
        <tr class="bg-gray-200">
            <th class="p-3">ID</th>
            <th class="p-3">Nombre</th>
            <th class="p-3">Tipo</th>
            <th class="p-3">Especificación</th>
            <th class="p-3">Imagen</th>
            <th class="p-3">Imagen 1</th>
            <th class="p-3">Imagen 2</th>
            <th class="p-3">Imagen 3</th>
            <th class="p-3">Acciones</th>
        </tr>
    </thead>
    <tbody>
    @if(isset($servicios))
        @foreach ($servicios as $servicio)
        <tr class="border-b">
            <td class="p-3">{{ $servicio->id }}</td>
            <td class="p-3">{{ $servicio->servicio }}</td>
            <td class="p-3">{{ $servicio->tipo }}</td>
            <td class="p-3">{{ $servicio->especificacion }}</td>
            <td class="p-3">
                <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="Imagen del servicio" class="w-20 h-20 object-cover">
            </td>
            <td>
                <img src="{{ asset('storage/' . $servicio->imagen1) }}" alt="Imagen del servicio" class="w-20 h-20 object-cover">
            </td>
            <td>
                <img src="{{ asset('storage/' . $servicio->imagen2) }}" alt="Imagen del servicio" class="w-20 h-20 object-cover">
            </td>
            <td>
                <img src="{{ asset('storage/' . $servicio->imagen3) }}" alt="Imagen del servicio" class="w-20 h-20 object-cover">
            </td>
            <td class="p-3">
                <form action="{{ route('dashboard.destroy', ['section' => 'servicios', 'id' => $servicio->id]) }}" method="POST" onsubmit="return confirm('¿Eliminar servicio?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
        @else
        <p>No hay servicios disponibles</p>
        @endif
    </tbody>
</table>

<!-- Modal para agregar servicio -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServiceModalLabel">Agregar Nuevo Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dashboard.store', ['section' => 'servicios']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="servicio" class="form-label">Nombre del Servicio</label>
                        <input type="text" name="servicio" id="servicio" class="form-control" placeholder="Nombre del servicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <input type="text" name="tipo" id="tipo" class="form-control" placeholder="Tipo del servicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="especificacion" class="form-label">Especificación</label>
                        <input type="text" name="especificacion" id="especificacion" class="form-control" placeholder="Especificación del servicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="imagen1" class="form-label">Imagen 1</label>
                        <input type="file" name="imagen1" id="imagen1" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="imagen2" class="form-label">Imagen 2</label>
                        <input type="file" name="imagen2" id="imagen2" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="imagen3" class="form-label">Imagen 3</label>
                        <input type="file" name="imagen3" id="imagen3" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar Servicio</button>
                </form>
            </div>
        </div>
    </div>
</div>