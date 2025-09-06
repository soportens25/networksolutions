<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TechnicianStatus;
use App\Models\User;
use App\Models\TicketCategory;
use App\Notifications\TicketAssigned;
use App\Notifications\TicketCreated;
use App\Notifications\TicketStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $ticketsQuery = Ticket::with(['user', 'assignedUser', 'empresa']);

        if ($user->hasAnyRole(['admin', 'tecnico'])) {
            // Admin y técnico ven TODOS los tickets
            $tickets = $ticketsQuery;
        } else {
            // Usuarios empresariales solo ven tickets de sus empresas
            $empresaIds = $user->empresas->pluck('id')->toArray();
            $tickets = $ticketsQuery->whereIn('empresa_id', $empresaIds);
        }

        // Aplicar filtros adicionales si existen
        if ($request->filled('search')) {
            $search = $request->search;
            $tickets->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filtros opcionales
        if ($request->filled('status')) {
            $ticketsQuery->where('status', $request->status);
        }

        if ($request->filled('technician_id')) {
            $ticketsQuery->where('technician_id', $request->technician_id);
        }

        if ($request->filled('search')) {
            $ticketsQuery->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('id', 'like', '%' . $request->search . '%');
            });
        }

        $tickets = $ticketsQuery->paginate(15);

        // CORREGIDO: Buscar técnicos correctamente
        try {
            // Buscar usuarios con rol 'tecnico' o 'admin'
            $technicians = User::role(['tecnico', 'admin'])->get();

            // Para debugging temporal (eliminar después):
            if ($technicians->isEmpty()) {
                Log::warning('No se encontraron técnicos. Usando todos los usuarios.');
                $technicians = User::all();
            }
        } catch (\Exception $e) {
            Log::error('Error obteniendo técnicos: ' . $e->getMessage());
            $technicians = User::all(); // Fallback
        }

        $categories = [];
        try {
            if (class_exists('App\Models\TicketCategory')) {
                $categories = TicketCategory::where('is_active', true)->get();
            }
        } catch (\Exception $e) {
            $categories = [];
        }

        $currentStatus = TechnicianStatus::where('user_id', $user->id)->first();

        // Estadísticas básicas
        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'assigned' => Ticket::where('status', 'assigned')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
            'avg_resolution_time' => 0,
        ];

        $filters = $request->only(['status', 'technician_id', 'search']);

        return view('tickets.index', compact(
            'tickets',
            'technicians',
            'categories',
            'currentStatus',
            'stats',
            'filters'
        ));
    }

    public function store(Request $request)
    {
        // ⭐ VALIDACIÓN ACTUALIZADA
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'empresa_id' => 'nullable|exists:empresas,id', // Opcional para admin/técnico
        ]);

        try {
            $user = Auth::user();
            $technicianStatus = TechnicianStatus::where('status', 'available')->first();
            $status = $technicianStatus ? 'assigned' : 'pending';

            // ⭐ LÓGICA PARA ASIGNAR EMPRESA
            $empresaId = null;

            if ($user->hasAnyRole(['admin', 'tecnico'])) {
                // Admin/Técnico pueden especificar empresa o dejarla vacía
                $empresaId = $request->empresa_id;
            } else {
                // Usuarios empresariales: usar su primera empresa automáticamente
                $empresaId = $user->empresas->first()?->id;

                if (!$empresaId) {
                    return back()->withInput()
                        ->withErrors(['error' => 'No tienes una empresa asignada. Contacta al administrador.']);
                }
            }

            // ⭐ DATOS DEL TICKET CON EMPRESA
            $ticketData = [
                'user_id' => $user->id,
                'technician_id' => $technicianStatus?->user_id,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'status' => $status,
                'empresa_id' => $empresaId, // ⭐ EMPRESA ASIGNADA
            ];

            // Crear ticket
            $ticket = Ticket::create($ticketData);

            // Actualizar estado del técnico
            if ($technicianStatus) {
                $technicianStatus->update(['status' => 'busy']);
            }

            // Enviar notificaciones
            try {
                $this->sendTicketCreatedNotifications($ticket);
            } catch (\Exception $e) {
                Log::error('Error enviando notificaciones:', ['error' => $e->getMessage()]);
            }

            return redirect()->route('tickets.index')
                ->with('success', 'Ticket creado correctamente. ID: #' . $ticket->id);
        } catch (\Exception $e) {
            Log::error('Error creando ticket:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withInput()
                ->withErrors(['error' => 'Error específico: ' . $e->getMessage()]);
        }
    }

    public function assignTicket(Request $request, $ticketId)
    {
        if (!Auth::user()->hasRole('admin')) {
            return back()->withErrors(['error' => 'No tienes permisos para asignar tickets.']);
        }

        $request->validate([
            'technician_id' => 'required|exists:users,id',
        ]);

        try {
            $ticket = Ticket::findOrFail($ticketId);
            $previousTechnician = $ticket->technician_id;
            $newTechnician = $request->technician_id;

            // Actualizar ticket
            $ticket->update([
                'technician_id' => $newTechnician,
                'status' => 'assigned',
            ]);

            // Actualizar estados de técnicos
            if ($previousTechnician && $previousTechnician != $newTechnician) {
                TechnicianStatus::where('user_id', $previousTechnician)
                    ->update(['status' => 'available']);
            }

            TechnicianStatus::updateOrCreate(
                ['user_id' => $newTechnician],
                ['status' => 'busy']
            );

            // Enviar notificaciones
            try {
                $this->sendTicketAssignedNotifications($ticket);
            } catch (\Exception $e) {
                Log::error('Error enviando notificaciones:', ['error' => $e->getMessage()]);
            }

            $message = $previousTechnician ? 'Ticket reasignado correctamente.' : 'Ticket asignado correctamente.';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error asignando ticket:', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al asignar el ticket: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::with('user', 'assignedUser')->findOrFail($id);
        $user = Auth::user();

        // Verificar permisos
        if (!$user->hasRole('admin') && $ticket->technician_id !== $user->id) {
            return back()->withErrors(['error' => 'No tienes permisos para actualizar este ticket.']);
        }

        if ($request->has('status')) {
            $oldStatus = $ticket->status;
            $newStatus = $request->status;

            // **DEBUGGING - Agregar logs**
            Log::info('Cambio de estado detectado:', [
                'ticket_id' => $ticket->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'user_id' => Auth::id()
            ]);

            $ticket->update(['status' => $newStatus]);

            // **VERIFICAR QUE EL ESTADO REALMENTE CAMBIÓ**
            if ($oldStatus !== $newStatus) {
                Log::info('Estados son diferentes, enviando notificaciones...');

                // Liberar técnico si se cierra o resuelve
                if (in_array($newStatus, ['resolved', 'closed']) && $ticket->technician_id) {
                    TechnicianStatus::where('user_id', $ticket->technician_id)
                        ->update(['status' => 'available']);

                    // Auto-asignar ticket pendiente si hay alguno
                    $pendingTicket = Ticket::where('status', 'pending')->first();
                    if ($pendingTicket) {
                        $pendingTicket->update([
                            'technician_id' => $ticket->technician_id,
                            'status' => 'assigned',
                        ]);

                        TechnicianStatus::where('user_id', $ticket->technician_id)
                            ->update(['status' => 'busy']);
                    }
                }

                // **ENVIAR NOTIFICACIONES DE CAMBIO DE ESTADO - MEJORADO**
                Log::info('Iniciando envío de notificaciones de cambio de estado...');

                try {
                    // Notificar al creador del ticket
                    if ($ticket->user && $ticket->user->email && $ticket->user->id !== Auth::id()) {
                        Log::info('Enviando notificación al creador:', [
                            'user_id' => $ticket->user->id,
                            'email' => $ticket->user->email
                        ]);

                        $ticket->user->notify(new TicketStatusChanged($ticket, $oldStatus, $newStatus));
                        Log::info('✅ Notificación al creador enviada exitosamente');
                    } else {
                        Log::info('No se envía notificación al creador:', [
                            'user_exists' => $ticket->user ? 'yes' : 'no',
                            'has_email' => $ticket->user && $ticket->user->email ? 'yes' : 'no',
                            'same_user' => $ticket->user && $ticket->user->id === Auth::id() ? 'yes' : 'no'
                        ]);
                    }

                    // Notificar al técnico asignado
                    if ($ticket->assignedUser && $ticket->assignedUser->email && $ticket->assignedUser->id !== Auth::id()) {
                        Log::info('Enviando notificación al técnico:', [
                            'user_id' => $ticket->assignedUser->id,
                            'email' => $ticket->assignedUser->email
                        ]);

                        $ticket->assignedUser->notify(new TicketStatusChanged($ticket, $oldStatus, $newStatus));
                        Log::info('✅ Notificación al técnico enviada exitosamente');
                    } else {
                        Log::info('No se envía notificación al técnico:', [
                            'user_exists' => $ticket->assignedUser ? 'yes' : 'no',
                            'has_email' => $ticket->assignedUser && $ticket->assignedUser->email ? 'yes' : 'no',
                            'same_user' => $ticket->assignedUser && $ticket->assignedUser->id === Auth::id() ? 'yes' : 'no'
                        ]);
                    }

                    // PRUEBA FORZADA - Para debugging (eliminar después)
                    Log::info('🧪 ENVIANDO NOTIFICACIÓN FORZADA PARA TESTING...');
                    $testUser = User::where('email', '!=', '')->first();
                    if ($testUser) {
                        $testUser->notify(new TicketStatusChanged($ticket, $oldStatus, $newStatus));
                        Log::info('✅ Notificación FORZADA enviada a: ' . $testUser->email);
                    }
                } catch (\Exception $e) {
                    Log::error('❌ Error enviando notificaciones de cambio de estado:', [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else {
                Log::info('Estados son iguales, no se envían notificaciones');
            }

            $statusName = ucfirst(str_replace('_', ' ', $newStatus));
            return redirect()->back()->with('success', "Ticket actualizado a: {$statusName}");
        }

        return redirect()->back()->with('info', 'No se realizaron cambios.');
    }

    public function show($id)
    {
        $ticket = Ticket::with('assignedUser', 'user')->findOrFail($id);
        $messages = $ticket->messages() ? $ticket->messages()->with('user')->orderBy('created_at', 'asc')->get() : collect();

        return view('tickets.show', compact('ticket', 'messages'));
    }

    public function updateTechnicianStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:available,busy',
        ]);

        $technicianStatus = TechnicianStatus::updateOrCreate(
            ['user_id' => Auth::id()],
            ['status' => $request->status]
        );

        if ($request->status === 'available') {
            $ticket = Ticket::where('status', 'pending')->first();
            if ($ticket) {
                $ticket->update([
                    'technician_id' => Auth::id(),
                    'status' => 'assigned',
                ]);

                $technicianStatus->update(['status' => 'busy']);
                $this->sendTicketAssignedNotifications($ticket);
            }
        }

        return redirect()->back()->with('info', 'Tu estado ha sido actualizado.');
    }

    public function selfAssign($id)
    {
        $ticket = Ticket::whereNull('technician_id')->where('id', $id)->firstOrFail();

        $ticket->update([
            'technician_id' => Auth::id(),
            'status' => 'assigned',
        ]);

        TechnicianStatus::updateOrCreate(
            ['user_id' => Auth::id()],
            ['status' => 'busy']
        );

        $this->sendTicketAssignedNotifications($ticket);

        return redirect()->back()->with('success', 'Te has asignado el ticket.');
    }

    public function create()
    {
        $categories = [];
        try {
            if (class_exists('App\Models\TicketCategory')) {
                $categories = TicketCategory::where('is_active', true)->get();
            }
        } catch (\Exception $e) {
            $categories = [];
        }

        return view('tickets.create', compact('categories'));
    }

    // MÉTODOS PRIVADOS
    private function sendTicketCreatedNotifications($ticket)
    {
        // 1. Notificar a todos los admins
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            if ($admin->email) {
                $admin->notify(new TicketCreated($ticket, 'admin'));
            }
        }

        // 2. Notificar al creador del ticket (confirmación)
        if ($ticket->user && $ticket->user->email) {
            $ticket->user->notify(new TicketCreated($ticket, 'creator'));
        }

        // 3. Si se asignó automáticamente, notificar al técnico
        if ($ticket->technician_id && $ticket->assignedUser && $ticket->assignedUser->email) {
            $ticket->assignedUser->notify(new TicketAssigned($ticket));
        }
    }

    private function sendTicketAssignedNotifications($ticket)
    {
        // 1. Notificar al técnico asignado
        if ($ticket->assignedUser && $ticket->assignedUser->email) {
            $ticket->assignedUser->notify(new TicketAssigned($ticket));
        }

        // 2. Notificar a todos los admins sobre la asignación
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            if ($admin->email) {
                $admin->notify(new TicketAssigned($ticket, 'admin_notification'));
            }
        }

        // 3. Notificar al creador del ticket
        if ($ticket->user && $ticket->user->email) {
            $ticket->user->notify(new TicketAssigned($ticket, 'creator_notification'));
        }
    }
}
