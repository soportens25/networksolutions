<div class="max-w-7xl mx-auto">

    <!-- Título y descripción -->
    <div class="mb-6">
        <h2 class="text-3xl font-semibold mb-2 text-gray-800">Usuarios</h2>
        <p class="text-gray-600">Aquí puedes gestionar todos los usuarios registrados en el sistema.</p>
    </div>

    <!-- Botón Agregar -->
    <div class="flex justify-end mb-4">
        <button
            type="button"
            class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded shadow flex items-center gap-2"
            data-bs-toggle="modal"
            data-bs-target="#addUserModal">
            <i class="ri-add-line"></i>
            Agregar Usuario
        </button>
    </div>

    <!-- Tabla de usuarios -->
    <div class="bg-gray-50 rounded-lg shadow-md border border-gray-200 overflow-auto">
        <table class="table-auto w-full">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="p-3 text-left">ID</th>
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Empresa</th>
                    <th class="p-3 text-left">Rol</th>
                    <th class="p-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b hover:bg-gray-100 transition">
                    <td class="p-3">{{ $user->id }}</td>
                    <td class="p-3">{{ $user->name }}</td>
                    <td class="p-3">{{ $user->email }}</td>
                    <td class="p-3 space-x-1">
                        @if($user->empresas->isNotEmpty())
                        @foreach($user->empresas as $empresa)
                        <span class="inline-block px-2 py-1 text-sm font-semibold text-blue-700 bg-blue-200 rounded-lg">
                            {{ $empresa->nombre_empresa }}
                        </span>
                        @endforeach
                        @else
                        <span class="inline-block px-2 py-1 text-sm text-gray-600 bg-gray-300 rounded-lg">
                            Sin empresa
                        </span>
                        @endif
                    </td>
                    <td class="p-3 space-x-1">
                        @if($user->roles->isNotEmpty())
                        @foreach($user->roles as $rol)
                        <span class="inline-block px-2 py-1 text-sm font-semibold text-white rounded-lg
                      {{ $rol->name==='Admin'?'bg-red-500':
                         ($rol->name==='Técnico'?'bg-green-500':'bg-gray-500') }}">
                            {{ $rol->name }}
                        </span>
                        @endforeach
                        @else
                        <span class="inline-block px-2 py-1 text-sm text-gray-600 bg-gray-300 rounded-lg">
                            Sin rol
                        </span>
                        @endif
                    </td>
                    <td class="p-3 flex space-x-2">
                        <button
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1"
                            data-bs-toggle="modal"
                            data-bs-target="#editUserModal{{ $user->id }}">
                            <i class="ri-edit-line"></i>
                            Editar
                        </button>
                        <form
                            action="{{ route('dashboard.destroy',['section'=>'usuarios','id'=>$user->id]) }}"
                            method="POST"
                            onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded flex items-center gap-1">
                                <i class="ri-delete-bin-line"></i>
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500">
                        No hay usuarios disponibles.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Agregar Usuario -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-green-500 text-white">
                    <h5 class="modal-title" id="addUserModalLabel">Agregar Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('dashboard.store',['section'=>'usuarios']) }}" method="POST">
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

                        <!-- Empresa -->
                        <div class="mb-3">
                            <label for="empresa" class="form-label">Empresa</label>
                            <select name="empresa" id="empresa" class="form-select" required>
                                <option value="">Selecciona una empresa</option>
                                @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre_empresa }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rol -->
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select name="rol" id="rol" class="form-select" required>
                                <option value="">Selecciona un rol</option>
                                @foreach($roles as $rol)
                                <option value="{{ $rol->name }}">{{ $rol->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="ri-check-line me-1"></i>Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales para Editar (uno por cada usuario) -->
    @foreach($users as $user)
    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-blue-500 text-white">
                    <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Editar Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('dashboard.update',['section'=>'usuarios','id'=>$user->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name_{{ $user->id }}" class="form-label">Nombre</label>
                            <input type="text" id="name_{{ $user->id }}" name="name" class="form-control" value="{{ old('name',$user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email_{{ $user->id }}" class="form-label">Email</label>
                            <input type="email" id="email_{{ $user->id }}" name="email" class="form-control" value="{{ old('email',$user->email) }}" required>
                        </div>

                        <!-- Empresa para editar -->
                        <div class="mb-3">
                            <label for="empresa_{{ $user->id }}" class="form-label">Empresa</label>
                            <select id="empresa_{{ $user->id }}" name="empresa" class="form-select" required>
                                <option value="">Selecciona una empresa</option>
                                @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}" {{ old('empresa',$user->empresas->pluck('id')->first())==$empresa->id?'selected':'' }}>
                                    {{ $empresa->nombre_empresa }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rol para editar -->
                        <div class="mb-3">
                            <label for="rol_{{ $user->id }}" class="form-label">Rol</label>
                            <select id="rol_{{ $user->id }}" name="rol" class="form-select" required>
                                <option value="">Selecciona un rol</option>
                                @foreach($roles as $rolOption)
                                <option value="{{ $rolOption->name }}" {{ $user->roles->pluck('name')->contains($rolOption->name)?'selected':'' }}>
                                    {{ $rolOption->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>