<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel de Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100 min-h-screen py-10 mx-4 md:mx-20 mb-6">

    <div class="bg-indigo-600 text-white py-4 px-6 shadow-md rounded ">
        <h1 class="text-xl font-bold flex items-center gap-2">
            <i class="ri-ticket-2-line text-2xl"></i>
            Listado de Tickets
        </h1>
    </div>

    <div class="container mx-auto px-4 py-10">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">🎫 Lista de Tickets</h1>
            <a href="{{ route('tickets.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition">
                + Crear Ticket
            </a>
        </div>

        <!-- Mensajes de sesión -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4 shadow">
                {{ session('info') }}
            </div>
        @endif

        <!-- Cambiar estado del técnico -->
        @role('tecnico')
            <div class="mb-6">
                <form method="POST" action="{{ route('technician.status.update') }}" class="inline-flex items-center gap-2">
                    @csrf
                    <label for="status" class="font-semibold">Tu estado:</label>
                    <select name="status"
                        class="form-select px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-300"
                        onchange="this.form.submit()">
                        <option value="available" {{ $currentStatus == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="busy" {{ $currentStatus == 'busy' ? 'selected' : '' }}>Ocupado</option>
                    </select>
                </form>
            </div>
        @endrole

        <!-- Filtros -->
        <form method="GET" action="{{ route('tickets.index') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-6 rounded-2xl shadow-xl mb-10">
            <div>
                <label class="block text-sm font-medium text-gray-700">Estado</label>
                <select name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm">
                    <option value="">Todos</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Abierto</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Proceso</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resuelto</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Cerrado</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Técnico</label>
                <select name="technician_id"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm">
                    <option value="">Todos</option>
                    @foreach ($technicians as $technician)
                        <option value="{{ $technician->id }}"
                            {{ request('technician_id') == $technician->id ? 'selected' : '' }}>
                            {{ $technician->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                    class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition shadow">
                    Filtrar
                </button>
            </div>
        </form>

        <!-- Tabla de Tickets -->
        <div class="overflow-x-auto bg-white rounded-2xl shadow-xl">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Título</th>
                        <th class="px-6 py-3">Descripción</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Técnico</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">{{ $ticket->id }}</td>
                            <td class="px-6 py-4">{{ $ticket->title }}</td>
                            <td class="px-6 py-4">{{ Str::limit($ticket->description, 40) }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $ticket->status == 'open' ? 'bg-green-200 text-green-800' : ($ticket->status == 'in_progress' ? 'bg-yellow-200 text-yellow-800' : ($ticket->status == 'resolved' ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>

                                @role('tecnico')
                                    @if ($ticket->technician_id === auth()->id())
                                        <form method="POST" action="{{ route('tickets.update', $ticket->id) }}"
                                            class="inline-block ml-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="status"
                                                class="text-xs border rounded px-2 py-1"
                                                onchange="this.form.submit()">
                                                <option disabled selected>...</option>
                                                <option value="in_progress">En proceso</option>
                                                <option value="resolved">Resuelto</option>
                                                <option value="closed">Cerrado</option>
                                            </select>
                                        </form>
                                    @endif
                                @endrole
                            </td>
                            <td class="px-6 py-4">{{ $ticket->assignedUser->name ?? 'No asignado' }}</td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">Ver</a>
                                @role('tecnico')
                                    @if (is_null($ticket->technician_id))
                                        <form method="POST" action="{{ route('tickets.selfAssign', $ticket->id) }}"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">
                                                Asignarme
                                            </button>
                                        </form>
                                    @endif
                                @endrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay tickets registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $tickets->appends(request()->query())->links() }}
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ticketList', () => ({
            tickets: @json($tickets),
            statusFilter: '',
            dateFilter: '',
            searchQuery: '',

            get filteredTickets() {
                return this.tickets.filter(ticket => {
                    return (this.statusFilter === '' || ticket.status === this
                            .statusFilter) &&
                        (this.dateFilter === '' || ticket.created_at.startsWith(this
                            .dateFilter)) &&
                        (ticket.title.toLowerCase().includes(this.searchQuery
                            .toLowerCase()));
                });
            }
        }));
    });
</script>

