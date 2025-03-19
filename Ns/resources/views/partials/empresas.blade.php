
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">📋 Lista de Empresas</h2>
        <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#empresaModal">
            <i class="bi bi-plus-lg me-2"></i> Agregar Empresa
        </button>
    </div>

    <!-- Tabla de Empresas -->
    <div class="table-responsive shadow-lg p-3 bg-white rounded">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>NIT</th>
                    <th>Logo</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($empresas as $empresa)
                <tr>
                    <td>{{ $empresa->id }}</td>
                    <td>{{ $empresa->nombre_empresa }}</td>
                    <td>{{ $empresa->nit }}</td>
                    <td>
                        @if($empresa->logo)
                            <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo" width="100" height="100">
                        @else
                            <span class="badge bg-secondary">Sin logo</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                        <form action="{{ route('dashboard.destroy', ['section' => 'empresas', 'id' => $empresa->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta empresa?')">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar empresa -->
<div class="modal fade" id="empresaModal" tabindex="-1" aria-labelledby="empresaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="empresaModalLabel">Agregar Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dashboard.store', ['section' => 'empresas']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre_empresa" class="form-label fw-bold">Nombre de la Empresa</label>
                        <input type="text" class="form-control" name="nombre_empresa" required>
                    </div>
                    <div class="mb-3">
                        <label for="nit" class="form-label fw-bold">NIT</label>
                        <input type="text" class="form-control" name="nit" required>
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label fw-bold">Logo</label>
                        <input type="file" class="form-control" name="logo" accept="image/*">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar Empresa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>