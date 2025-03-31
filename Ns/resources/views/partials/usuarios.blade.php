<h2 class="text-3xl font-semibold mb-4">Usuarios</h2>
<p>Aquí puedes gestionar todos los usuarios registrados en el sistema.</p>

<!-- Botón para abrir el modal -->
<div class="flex justify-end mb-4">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        Agregar Usuario
    </button>
</div>

<!-- Tabla de usuarios -->
<!-- Tabla de usuarios -->
<table class="table-auto w-full bg-white shadow-md rounded-lg">
    <thead>
        <tr class="bg-gray-200 text-gray-700">
            <th class="p-3">ID</th>
            <th class="p-3">Nombre</th>
            <th class="p-3">Email</th>
            <th class="p-3">Empresa</th>
            <th class="p-3">Rol</th>
            <th class="p-3">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr class="border-b hover:bg-gray-100 transition">
                <td class="p-3">{{ $user->id }}</td>
                <td class="p-3">{{ $user->name }}</td>
                <td class="p-3">{{ $user->email }}</td>

                <!-- Empresas con estilos -->
                <td class="p-3">
                    @if ($user->empresas->isNotEmpty())
                        @foreach ($user->empresas as $empresa)
                            <span class="px-2 py-1 text-sm font-semibold text-blue-700 bg-blue-200 rounded-lg">
                                {{ $empresa->nombre_empresa }}
                            </span>
                        @endforeach
                    @else
                        <span class="px-2 py-1 text-sm text-gray-600 bg-gray-300 rounded-lg">Sin empresa</span>
                    @endif
                </td>

                <!-- Roles con estilos -->
                <td class="p-3">
                    @if ($user->roles->isNotEmpty())
                        @foreach ($user->roles as $rol)
                            <span class="px-2 py-1 text-sm font-semibold text-white 
                                {{ $rol->name == 'Admin' ? 'bg-red-500' : 
                                    ($rol->name == 'Técnico' ? 'bg-green-500' : 'bg-gray-500') }} 
                                rounded-lg">
                                {{ $rol->name }}
                            </span>
                        @endforeach
                    @else
                        <span class="px-2 py-1 text-sm text-gray-600 bg-gray-300 rounded-lg">Sin rol</span>
                    @endif
                </td>

                <td class="p-3 flex space-x-2">
                    <button class="btn btn-warning">Editar</button>
                    <form action="{{ route('dashboard.destroy', ['section' => 'usuarios', 'id' => $user->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal para agregar un nuevo usuario -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Agregar Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dashboard.store', ['section' => 'usuarios']) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nombre del usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Correo electrónico" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirmar contraseña" required>
                    </div>
                    <!-- Campo para seleccionar Empresa -->
                    <div class="mb-3">
                        <label for="empresa" class="form-label">Empresa</label>
                        <select name="empresa" id="empresa" class="form-control" required>
                            <option value="">Selecciona una empresa</option>
                            @foreach ($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre_empresa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Campo para seleccionar Rol -->
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select name="rol" id="rol" class="form-control" required>
                            <option value="">Selecciona un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->name }}">{{ $rol->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar Usuario</button>
                </form>
            </div>
        </div>
    </div>
</div>
