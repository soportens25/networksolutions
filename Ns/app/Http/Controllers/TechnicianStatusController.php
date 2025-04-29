<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TechnicianStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\TicketAssigned;

class TechnicianStatusController extends Controller
{
    public function index()
    {
        if (request()->wantsJson()) {
            $tickets = Ticket::where('user_id', Auth::id())->get();
            return response()->json($tickets);
        }

        $tickets = Ticket::with('assignedUser')->get();
        return view('tickets.index', ['tickets' => $tickets]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            
        ]);

        // Buscar técnico disponible en TechnicianStatus
        $technicianStatus = TechnicianStatus::where('status', 'available')->first();

        if (!$technicianStatus) {
            return response()->json(['error' => 'No hay técnicos disponibles'], 400);
        }

        // Crear ticket
        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'technician_id' => $technicianStatus->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'open',
        ]);

        // Marcar técnico como ocupado
        $technicianStatus->update(['status' => 'busy']);

        // Emitir evento opcional
        broadcast(new TicketAssigned($ticket, $technicianStatus->user->name))->toOthers();

        return response()->json($ticket, 201);
    }

    public function show($id)
    {
        $ticket = Ticket::with('messages.user')->findOrFail($id);

        // Si se accede desde Blade
        if (!request()->wantsJson()) {
            return view('tickets.show', [
                'ticket' => $ticket,
                'user_id' => Auth::id(),
            ]);
        }

        return response()->json($ticket);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($request->has('status')) {
            $ticket->update(['status' => $request->status]);

            // Si se cierra o resuelve el ticket, liberar técnico
            if (in_array($request->status, ['resolved', 'closed'])) {
                TechnicianStatus::where('user_id', $ticket->technician_id)
                    ->update(['status' => 'available']);
            }
        }

        return response()->json($ticket);
    }

    public function assignTicket($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        // Buscar técnico disponible por rol
        $technician = User::role('tecnico')->whereHas('technicianStatus', function ($q) {
            $q->where('status', 'available');
        })->first();

        if (!$technician) {
            return response()->json(['error' => 'No hay técnicos disponibles'], 400);
        }

        $ticket->technician_id = $technician->id;
        $ticket->status = 'open';
        $ticket->save();

        // Marcar técnico como ocupado
        TechnicianStatus::updateOrCreate(
            ['user_id' => $technician->id],
            ['status' => 'busy']
        );

        broadcast(new TicketAssigned($ticket, $technician->name))->toOthers();

        return response()->json(['message' => 'Ticket asignado a ' . $technician->name]);
    }
    public function updateStatus(Request $request)
{
    $request->validate([
        'status' => 'required|in:available,busy',
    ]);

    $technicianStatus = TechnicianStatus::updateOrCreate(
        ['user_id' => Auth::id()],
        ['status' => $request->status]
    );

    // Si el técnico está disponible, buscar ticket pendiente
    if ($request->status === 'available') {
        $ticket = \App\Models\Ticket::where('status', 'pending')->first();
        if ($ticket) {
            $ticket->update([
                'technician_id' => Auth::id(),
                'status' => 'open',
            ]);

            $technicianStatus->update(['status' => 'busy']);

            broadcast(new TicketAssigned($ticket, Auth::user()->name))->toOthers();
        }
    }

    return response()->json($technicianStatus);
}

}
