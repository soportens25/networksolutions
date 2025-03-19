<div class="container mt-5">
    <h2 class="mb-4 text-primary fw-bold">📋 Inventario de Equipos</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="ri-add-line me-2"></i> Agregar Equipo
        </button>
        <a href="{{ route('dashboard.exportExcel', ['section' => 'inventarios']) }}"
            class="btn btn-primary d-flex align-items-center">
            <i class="ri-file-excel-2-line me-2"></i> Exportar Excel
        </a>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Sticker</th>
                    <th>Marca</th>
                    <th>Tipo</th>
                    <th>SO</th>
                    <th>Serial</th>
                    <th>Empresa</th>
                    <th>Acciones</th>
                    <th>Adicional</th>
                </tr>
            </thead>
            <tbody class="table-light">
                @foreach ($inventarios as $item)
                    <tr>
                        <td class="fw-bold">{{ $item->id }}</td>
                        <td>{{ $item->nombre_equipo }}</td>
                        <td>{{ $item->sticker }}</td>
                        <td>{{ $item->marca_equipo }}</td>
                        <td>{{ $item->tipo_equipo }}</td>
                        <td>{{ $item->sistema_operativo }}</td>
                        <td class="text-muted">{{ $item->numero_serial }}</td>
                        <td>{{ $item->empresa->nombre_empresa ?? 'N/D' }}</td>
                        <td>
                            <a href="{{ route('dashboard.exportPdf', ['section' => 'inventarios', 'id' => $item->id]) }}"
                                class="btn btn-secondary btn-sm mb-2">
                                <i class="ri-file-pdf-2-line"></i> PDF
                            </a>
                            <form
                                action="{{ route('dashboard.destroy', ['section' => 'inventarios', 'id' => $item->id]) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Eliminar este equipo?')">
                                    <i class="ri-delete-bin-6-line"></i> Eliminar
                                </button>
                            </form>
                        </td>
                        <td>
                            <!-- Botón para abrir el modal de Historial de Mantenimiento -->
                            <button type="button" class="btn btn-primary btn-sm mb-2" data-bs-toggle="modal"
                                data-bs-target="#modalMantenimiento-{{ $item->id }}">
                                <i class="ri-tools-line"></i> Mantenimiento
                            </button>

                            <!-- Botón para abrir el modal de Personal Encargado -->
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalEncargado-{{ $item->id }}">
                                <i class="ri-user-add-line"></i> Encargado
                            </button>
                        </td>
                    </tr>

                    <!-- Modal para Historial de Mantenimiento -->
                    <div class="modal fade" id="modalMantenimiento-{{ $item->id }}" tabindex="-1"
                        aria-labelledby="modalMantenimientoLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalMantenimientoLabel">Agregar Mantenimiento</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <form
                                        action="{{ route('dashboard.store', ['section' => 'historial_mantenimiento']) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="id_equipo" value="{{ $item->id }}">
                                        <div class="mb-3">
                                            <label class="form-label">Encargado</label>
                                            <input type="text" name="encargado" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tipo de Mantenimiento</label>
                                            <input type="text" name="tipo_mantenimiento" class="form-control"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha</label>
                                            <input type="date" name="fecha_mantenimiento" class="form-control"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Descripción</label>
                                            <textarea name="descripcion" class="form-control" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Observaciones</label>
                                            <textarea name="observaciones" class="form-control"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success">Guardar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para Personal Encargado -->
                    <div class="modal fade" id="modalEncargado-{{ $item->id }}" tabindex="-1"
                        aria-labelledby="modalEncargadoLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEncargadoLabel">Asignar Personal Encargado</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('dashboard.store', ['section' => 'personal_encargado']) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="id_equipo" value="{{ $item->id }}">
                                        <div class="mb-3">
                                            <label class="form-label">Usuario Responsable</label>
                                            <input type="text" name="usuario_responsable" class="form-control"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Área de Ubicación</label>
                                            <input type="text" name="area_ubicacion" class="form-control"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha de Asignación</label>
                                            <input type="date" name="fecha_asignacion" class="form-control"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha de Devolución</label>
                                            <input type="date" name="fecha_devolucion" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Observación</label>
                                            <textarea name="observacion" class="form-control"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success">Guardar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar equipo -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addModalLabel">🖥️ Agregar Nuevo Equipo</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dashboard.store', ['section' => 'inventarios']) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_empresa" class="form-label">Empresa</label>
                            <select class="form-control" name="id_empresa" required>
                                <option value="">Selecciona una empresa</option>
                                @foreach ($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->nombre_empresa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre_equipo" class="form-label">Nombre del Equipo</label>
                            <input type="text" class="form-control" name="nombre_equipo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sticker" class="form-label">Sticker</label>
                            <input type="text" class="form-control" name="sticker">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="marca_equipo" class="form-label">Marca</label>
                            <input type="text" class="form-control" name="marca_equipo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tipo_equipo" class="form-label">Tipo</label>
                            <input type="text" class="form-control" name="tipo_equipo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sistema_operativo" class="form-label">Sistema Operativo</label>
                            <input type="text" class="form-control" name="sistema_operativo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="numero_serial" class="form-label">Número de Serie</label>
                            <input type="text" class="form-control" name="numero_serial" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="idioma" class="form-label">Idioma</label>
                            <input type="text" class="form-control" name="idioma" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="procesador" class="form-label">Procesador</label>
                            <input type="text" class="form-control" name="procesador" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="velocidad_procesador" class="form-label">Velocidad del Procesador</label>
                            <input type="text" class="form-control" name="velocidad_procesador" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tipo_conexion" class="form-label">Tipo de Conexión</label>
                            <input type="text" class="form-control" name="tipo_conexion" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ip" class="form-label">Dirección IP</label>
                            <input type="text" class="form-control" name="ip">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mac" class="form-label">Dirección MAC</label>
                            <input type="text" class="form-control" name="mac">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="memoria_ram" class="form-label">Memoria RAM</label>
                            <input type="text" class="form-control" name="memoria_ram" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cantidad_memoria" class="form-label">Cantidad de Memoria (GB)</label>
                            <input type="number" class="form-control" name="cantidad_memoria" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="slots_memoria" class="form-label">Slots de Memoria</label>
                            <input type="number" class="form-control" name="slots_memoria" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="frecuencia_memoria" class="form-label">Frecuencia de Memoria</label>
                            <input type="number" class="form-control" name="frecuencia_memoria" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="version_bios" class="form-label">Versión de BIOS</label>
                            <input type="text" class="form-control" name="version_bios">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cantidad_discos" class="form-label">Cantidad de Discos</label>
                            <input type="number" class="form-control" name="cantidad_discos" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tipo_discos" class="form-label">Tipo de Discos</label>
                            <input type="text" class="form-control" name="tipo_discos" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="espacio_discos" class="form-label">Espacio de los Discos (GB)</label>
                            <input type="text" class="form-control" name="espacio_discos" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="grafica" class="form-label">Gráfica</label>
                            <input type="text" class="form-control" name="grafica">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="licencias" class="form-label">Licencias</label>
                            <textarea class="form-control" name="licencias"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="perifericos" class="form-label">Periféricos</label>
                            <textarea class="form-control" name="perifericos"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "order": [
                [0, "desc"]
            ], // Ordenar por ID descendente
            "pageLength": 10 // Cantidad de registros por página
        });
    });
</script>
