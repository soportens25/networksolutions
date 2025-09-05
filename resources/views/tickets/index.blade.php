<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .btn-purple {
            background-color: #8B5CF6;
            border-color: #8B5CF6;
            color: #fff;
        }

        .btn-purple:hover {
            background-color: #7C3AED;
            border-color: #7C3AED;
            color: #fff;
        }

        #chat-messages {
            max-height: calc(100vh - 32rem);
            overflow-y: auto;
        }

        .overflow-visible {
            overflow: visible !important;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="bg-orange-500 text-white p-6 rounded-xl shadow mb-8 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="ri-ticket-2-line text-3xl"></i>
                <h1 class="text-2xl font-semibold">Panel de Tickets</h1>
            </div>
            <div class="flex gap-6 text-center text-sm">
                <div>
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                    <div class="opacity-80">Total</div>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $stats['pending'] }}</div>
                    <div class="opacity-80">Pendientes</div>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ number_format($stats['avg_resolution_time'],1) }}h</div>
                    <div class="opacity-80">Prom. Resolución</div>
                </div>
            </div>
        </div>

        <!-- Título y acción -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">🎫 Lista de Tickets</h2>
            <a href="{{ route('tickets.create') }}"
                class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-5 py-2 rounded-lg shadow hover:from-orange-600 hover:to-orange-700 transition">
                + Crear Ticket
            </a>
        </div>

        <!-- Alertas -->
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow flex items-center">
            <i class="ri-check-circle-line text-xl mr-2"></i><span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('info'))
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded shadow flex items-center">
            <i class="ri-information-line text-xl mr-2"></i><span>{{ session('info') }}</span>
        </div>
        @endif
        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow">
            <div class="flex items-center mb-2">
                <i class="ri-error-warning-line text-xl mr-2"></i>
                <span class="font-semibold">Se encontraron errores:</span>
            </div>
            <ul class="list-disc ml-6">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Estado del técnico -->
        @role('tecnico')
        <div class="bg-gray-50 p-4 rounded-lg shadow border border-gray-200 mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="font-medium text-gray-800">Tu estado:</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $currentStatus->status==='available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    <span class="w-2 h-2 mr-2 rounded-full {{ $currentStatus->status==='available' ? 'bg-green-400' : 'bg-red-400' }}"></span>
                    {{ $currentStatus->status==='available' ? 'Disponible' : 'Ocupado' }}
                </span>
            </div>
            <form method="POST" action="{{ route('technician.status.update') }}">
                @csrf
                <select name="status" class="border px-3 py-2 rounded shadow-sm focus:ring-orange-400 focus:border-orange-400"
                    onchange="this.form.submit()">
                    <option value="available" {{ $currentStatus->status==='available' ? 'selected' : '' }}>Disponible</option>
                    <option value="busy" {{ $currentStatus->status==='busy' ? 'selected' : '' }}>Ocupado</option>
                </select>
            </form>
        </div>
        @endrole

        <!-- Filtros -->
        <div class="bg-gray-50 rounded-2xl shadow-lg border border-gray-200 p-6 mb-10">
            <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Búsqueda</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                        placeholder="ID, título, descripción..."
                        class="w-full px-3 py-2 border rounded focus:ring-orange-400 focus:border-orange-400 bg-white">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Estado</label>
                    <select name="status" class="w-full px-3 py-2 border rounded focus:ring-orange-400 focus:border-orange-400 bg-white">
                        <option value="">Todos</option>
                        <option value="pending" {{ ($filters['status'] ?? '')=='pending'?'selected':'' }}>Pendiente</option>
                        <option value="assigned" {{ ($filters['status'] ?? '')=='assigned'?'selected':'' }}>Asignado</option>
                        <option value="in_progress" {{ ($filters['status'] ?? '')=='in_progress'?'selected':'' }}>En proceso</option>
                        <option value="resolved" {{ ($filters['status'] ?? '')=='resolved'?'selected':'' }}>Resuelto</option>
                        <option value="closed" {{ ($filters['status'] ?? '')=='closed'?'selected':'' }}>Cerrado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Prioridad</label>
                    <select name="priority" class="w-full px-3 py-2 border rounded focus:ring-orange-400 focus:border-orange-400 bg-white">
                        <option value="">Todas</option>
                        <option value="urgent" {{ ($filters['priority'] ?? '')=='urgent'?'selected':'' }}>Urgente</option>
                        <option value="high" {{ ($filters['priority'] ?? '')=='high'?'selected':'' }}>Alta</option>
                        <option value="medium" {{ ($filters['priority'] ?? '')=='medium'?'selected':'' }}>Media</option>
                        <option value="low" {{ ($filters['priority'] ?? '')=='low'?'selected':'' }}>Baja</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Técnico</label>
                    <select name="technician_id" class="w-full px-3 py-2 border rounded focus:ring-orange-400 focus:border-orange-400 bg-white">
                        <option value="">Todos</option>
                        @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" {{ ($filters['technician_id'] ?? '')==$tech->id?'selected':'' }}>
                            {{ $tech->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition">
                        <i class="ri-search-line mr-1"></i>Filtrar
                    </button>
                    <a href="{{ route('tickets.index') }}" class="px-3 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de tickets -->
        <div class="bg-gray-50 rounded-2xl shadow-xl border border-gray-200 p-6 overflow-visible">
            <table class="min-w-full table-auto">
                <thead class="bg-orange-500 text-white">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Título</th>
                        <th class="px-4 py-2">Usuario</th>
                        <th class="px-4 py-2">Prioridad</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Técnico</th>
                        <th class="px-4 py-2">Creado</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-orange-50 transition">
                        <td class="px-4 py-3 font-mono">#{{ $ticket->id }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800">{{ Str::limit($ticket->title,40) }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($ticket->description,60) }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $ticket->user->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">Media</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                  @switch($ticket->status)
                    @case('pending') bg-gray-100 text-gray-800 @break
                    @case('assigned') bg-blue-100 text-blue-800 @break
                    @case('in_progress') bg-yellow-100 text-yellow-800 @break
                    @case('resolved') bg-green-100 text-green-800 @break
                    @case('closed') bg-slate-200 text-slate-800 @break
                    @default bg-gray-100 text-gray-800
                  @endswitch">
                                {{ ucfirst(str_replace('_',' ',$ticket->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $ticket->assignedUser->name ?? 'No asignado' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $ticket->created_at->format('d/m/Y H:i') }}<br>
                            {{ $ticket->created_at->diffForHumans() }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('tickets.show',$ticket->id) }}" class="bg-sky-500 text-white px-3 py-1 rounded text-xs hover:bg-sky-600 transition">
                                    Ver
                                </a>
                                @role('admin')
                                <button type="button" class="bg-purple-500 text-white px-3 py-1 rounded text-xs hover:bg-purple-600 transition" data-bs-toggle="modal" data-bs-target="#assignModal{{$ticket->id}}">
                                    {{ is_null($ticket->technician_id)?'Asignar':'Reasignar' }}
                                </button>
                                <select onchange="changeTicketStatus({{$ticket->id}}, this.value)" class="text-xs border rounded px-2 py-1 bg-white">
                                    <option value="">Cambiar estado...</option>
                                    @foreach(['pending','assigned','in_progress','resolved','closed'] as $st)
                                    @if($ticket->status!==$st)
                                    <option value="{{$st}}">{{ ucfirst(str_replace('_',' ',$st)) }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @endrole
                                @role('tecnico')
                                @if(is_null($ticket->technician_id) && $ticket->status==='pending')
                                <form method="POST" action="{{ route('tickets.selfAssign',$ticket->id) }}">
                                    @csrf
                                    <button type="submit" class="bg-yellow-400 text-white px-3 py-1 rounded text-xs hover:bg-yellow-500 transition">Asignarme</button>
                                </form>
                                @endif
                                @if($ticket->technician_id===auth()->id() && !in_array($ticket->status,['resolved','closed']))
                                <select onchange="changeTicketStatus({{$ticket->id}}, this.value)" class="text-xs border rounded px-2 py-1 bg-white">
                                    <option value="">Cambiar estado...</option>
                                    @foreach(['in_progress'=>'En proceso','resolved'=>'Resuelto'] as $val=>$label)
                                    @if($ticket->status!==$val)
                                    <option value="{{$val}}">{{$label}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @endif
                                @endrole
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            <i class="ri-ticket-2-line text-4xl mb-2"></i><br>
                            No hay tickets que coincidan.<br>
                            <a href="{{ route('tickets.create') }}" class="text-orange-500 hover:underline mt-2 inline-block">Crear el primer ticket</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Mostrando {{ $tickets->firstItem()??0 }} - {{ $tickets->lastItem()??0 }} de {{ $tickets->total() }} tickets
            </div>
            <div>{{ $tickets->appends(request()->query())->links() }}</div>
        </div>
    </div>

    <!-- Modales -->
    @foreach($tickets as $ticket)
    <div class="modal fade" id="assignModal{{$ticket->id}}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-purple-500 text-white">
                    <h5 class="modal-title">{{ is_null($ticket->technician_id)?'Asignar':'Reasignar' }} Ticket #{{$ticket->id}}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('tickets.assign',$ticket->id) }}">
                    @csrf
                    <div class="modal-body">
                        <label class="form-label fw-bold">Técnico:</label>
                        <select name="technician_id" class="form-select" required>
                            <option value="">Seleccionar técnico...</option>
                            @foreach($technicians as $tech)
                            <option value="{{$tech->id}}" {{$ticket->technician_id==$tech->id?'selected':''}}>
                                {{$tech->name}} @if($ticket->technician_id==$tech->id)(Actual)@endif
                            </option>
                            @endforeach
                        </select>
                        <div class="bg-gray-50 rounded p-3 mt-4">
                            <p><strong>Título:</strong> {{$ticket->title}}</p>
                            <p><strong>Creado por:</strong> {{$ticket->user->name}}</p>
                            <p><strong>Estado:</strong> <span class="badge bg-secondary">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span></p>
                            @if($ticket->technician_id)
                            <p><strong>Técnico actual:</strong> {{$ticket->assignedUser->name}}</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-purple">{{ is_null($ticket->technician_id)?'Asignar':'Reasignar' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function changeTicketStatus(id, status) {
            if (!status) return;
            if (['closed', 'resolved'].includes(status) && !confirm(`¿Seguro de cambiar a "${status}"?`)) return;
            const f = document.createElement('form');
            f.method = 'POST';
            f.action = `/tickets/${id}`;
            f.style.display = 'none';
            f.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">` +
                `<input type="hidden" name="_method" value="PUT">` +
                `<input type="hidden" name="status" value="${status}">`;
            document.body.append(f);
            f.submit();
        }
        @if($stats['pending'] > 0)
        setInterval(() => {
            if (!document.querySelector('.modal.show') && !['INPUT', 'SELECT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                location.reload();
            }
        }, 30000);
        @endif
    </script>
</body>

</html>