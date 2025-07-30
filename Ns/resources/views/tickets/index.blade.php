<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel de Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}">
</head>

<body class="bg-gradient-to-br from-orange-50 via-white to-stone-100 min-h-screen py-10">

    <div class="max-w-7xl mx-auto px-4">
        <!-- Título -->
        <div class="bg-orange-500 text-white py-4 px-6 rounded-xl shadow-lg mb-8 flex items-center gap-2">
            <i class="ri-ticket-2-line text-2xl"></i>
            <h1 class="text-xl font-semibold">Panel de Tickets</h1>
        </div>

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-slate-800">🎫 Lista de Tickets</h2>
            <a href="{{ route('tickets.create') }}"
                class="bg-gradient-to-r from-orange-500 to-orange-600 text-black no-underline px-5 py-2 rounded-lg shadow-md hover:from-orange-600 hover:to-orange-700 transition">
                + Crear Ticket
            </a>
        </div>

        <!-- Alertas -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded mb-4 shadow">
                {{ session('info') }}
            </div>
        @endif

        <!-- Estado del Técnico -->
        @role('tecnico')
            <div class="mb-6">
                <form method="POST" action="{{ route('technician.status.update') }}" class="flex items-center gap-3">
                    @csrf
                    <label class="text-slate-700 font-medium">Tu estado:</label>
                    <select name="status"
                        class="border border-slate-300 px-3 py-2 rounded-md shadow-sm focus:ring-orange-400 focus:border-orange-400"
                        onchange="this.form.submit()">
                        <option value="available" {{ $currentStatus == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="busy" {{ $currentStatus == 'busy' ? 'selected' : '' }}>Ocupado</option>
                    </select>
                </form>
            </div>
        @endrole

        <!-- Filtros -->
        <form method="GET" action="{{ route('tickets.index') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-6 rounded-2xl shadow-lg mb-10 border border-slate-200">
            <div>
                <label class="block text-sm font-medium text-slate-600">Estado</label>
                <select name="status"
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:ring-orange-400 focus:border-orange-400">
                    <option value="">Todos</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Abierto</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Proceso
                    </option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resuelto</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Cerrado</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600">Técnico</label>
                <select name="technician_id"
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:ring-orange-400 focus:border-orange-400">
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
                    class="w-full bg-orange-500 text-black border px-4 py-2 rounded-lg hover:bg-orange-600 transition shadow">
                    Filtrar
                </button>
            </div>
        </form>

        <!-- Tabla -->
        <div class="overflow-x-auto bg-white rounded-xl shadow-xl border border-slate-200">
            <table class="min-w-full text-sm text-left text-slate-700">
                <thead class="bg-orange-500 text-white">
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
                        <tr class="hover:bg-orange-50 transition">
                            <td class="px-6 py-4">{{ $ticket->id }}</td>
                            <td class="px-6 py-4">{{ $ticket->title }}</td>
                            <td class="px-6 py-4">{{ Str::limit($ticket->description, 40) }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                    @switch($ticket->status)
                                        @case('open') bg-green-100 text-green-800 @break
                                        @case('in_progress') bg-yellow-100 text-yellow-800 @break
                                        @case('resolved') bg-blue-100 text-blue-800 @break
                                        @case('closed') bg-slate-200 text-slate-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>

                                @role('tecnico')
                                    @if ($ticket->technician_id === auth()->id())
                                        <form method="POST" action="{{ route('tickets.update', $ticket->id) }}"
                                            class="inline ml-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="text-xs border rounded px-2 py-1"
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
                                    class="inline-block bg-sky-500 hover:bg-sky-600 text-white px-3 py-1 rounded text-xs">Ver</a>
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
                            <td colspan="6" class="px-6 py-4 text-center text-slate-500">No hay tickets registrados
                            </td>
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
