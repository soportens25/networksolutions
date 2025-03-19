<h2 class="text-3xl font-semibold mb-4">Facturas</h2>

<!-- Botón para abrir el modal de agregar factura -->
<div class="relative mb-4">
    <button type="button" class="absolute top-0 right-0 btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
        Agregar Factura
    </button>
</div>

<!-- Tabla de facturas -->
<table class="table-auto w-full bg-white shadow-md rounded-lg mt-20">
    <thead>
        <tr class="bg-gray-200">
            <th class="p-3">ID</th>
            <th class="p-3">Nombre del cliente</th>
            <th class="p-3">Dirección del cliente</th>
            <th class="p-3">Documento de identificación</th>
            <th class="p-3">Teléfono</th>
            <th class="p-3">Correo electrónico</th>
            <th class="p-3">Fecha</th>
            <th class="p-3">Subtotal</th>
            <th class="p-3">Total</th>
            <th class="p-3">Método de pago</th>
            <th class="p-3">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($facturas as $factura)
            <tr class="border-b">
                <td class="p-3">{{ $factura->id }}</td>
                <td class="p-3">{{ $factura->nombre_cliente }}</td>
                <td class="p-3">{{ $factura->direccion_cliente }}</td>
                <td class="p-3">{{ $factura->documento_cliente }}</td>
                <td class="p-3">{{ $factura->telefono_cliente }}</td>
                <td class="p-3">{{ $factura->correo_cliente }}</td>
                <td class="p-3">{{ $factura->fecha_emision }}</td>
                <td class="p-3">{{ $factura->subtotal }}</td>
                <td class="p-3">{{ $factura->total }}</td>
                <td class="p-3">{{ $factura->metodo_pago }}</td>
                <td class="p-3">
                    <form action="{{ route('dashboard.destroy', ['section' => 'facturas', 'id' => $factura->id]) }}" method="POST" onsubmit="return confirm('¿Eliminar factura?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal para agregar nueva factura -->
<div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInvoiceModalLabel">Agregar Nueva Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dashboard.store', ['section' => 'facturas']) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre_cliente" class="form-label">Nombre del cliente</label>
                        <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" placeholder="Nombre del cliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion_cliente" class="form-label">Dirección del cliente</label>
                        <input type="text" name="direccion_cliente" id="direccion_cliente" class="form-control" placeholder="Dirección del cliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="documento_cliente" class="form-label">Documento de identificación</label>
                        <input type="text" name="documento_cliente" id="documento_cliente" class="form-control" placeholder="Documento de identificación" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono_cliente" class="form-label">Teléfono</label>
                        <input type="text" name="telefono_cliente" id="telefono_cliente" class="form-control" placeholder="Teléfono" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo_cliente" class="form-label">Correo electrónico</label>
                        <input type="email" name="correo_cliente" id="correo_cliente" class="form-control" placeholder="Correo electrónico" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_emision" class="form-label">Fecha de emisión</label>
                        <input type="date" name="fecha_emision" id="fecha_emision" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="subtotal" class="form-label">Subtotal</label>
                        <input type="number" name="subtotal" id="subtotal" class="form-control" placeholder="Subtotal" required>
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total</label>
                        <input type="number" name="total" id="total" class="form-control" placeholder="Total" required>
                    </div>
                    <div class="mb-3">
                        <label for="metodo_pago" class="form-label">Método de pago</label>
                        <input type="text" name="metodo_pago" id="metodo_pago" class="form-control" placeholder="Método de pago" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar Factura</button>
                </form>
            </div>
        </div>
    </div>
</div>
