<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TechnicianStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\TicketAssigned;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Ticket::with('assignedUser', 'user'); // Asegúrate de incluir 'user' para el filtro por empresa
    
        if ($user->hasRole('tecnico')) {
            // Técnicos ven los suyos y los no asignados
            $query->where(function ($q) use ($user) {
                $q->whereNull('technician_id')
                    ->orWhere('technician_id', $user->id);
            });
        } elseif ($user->hasRole('empresarial')) {
            // Usuarios empresariales solo ven tickets de su(s) empresa(s)
            $empresaIds = $user->empresas()->pluck('empresas.id')->toArray();
    
            $query->whereHas('user.empresas', function ($q) use ($empresaIds) {
                $q->whereIn('empresas.id', $empresaIds);
            });
        }
        
        // Filtros opcionales
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }
    
        $tickets = $query->paginate(10);
        $technicians = User::role('tecnico')->get();
        $currentStatus = TechnicianStatus::where('user_id', $user->id)->first();
    
        return view('tickets.index', compact('tickets', 'technicians', 'currentStatus'));
    }
    
    

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $technicianStatus = TechnicianStatus::where('status', 'available')->first();
        $status = $technicianStatus ? 'open' : 'pending';

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'technician_id' => $technicianStatus?->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $status,
        ]);

        if ($technicianStatus) {
            $technicianStatus->update(['status' => 'busy']);
            broadcast(new TicketAssigned($ticket, $technicianStatus->user->name))->toOthers();
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket creado correctamente.');
    }

    public function show($id)
    {
        $ticket = Ticket::with('assignedUser')->findOrFail($id);
        $messages = $ticket->messages()->orderBy('created_at', 'asc')->get();

        return view('tickets.show', compact('ticket', 'messages'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($request->has('status')) {
            $ticket->update(['status' => $request->status]);

            if (in_array($request->status, ['resolved', 'closed'])) {
                TechnicianStatus::where('user_id', $ticket->technician_id)
                    ->update(['status' => 'available']);
            }
        }

        return redirect()->back()->with('info', 'Estado del ticket actualizado.');
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
                    'status' => 'open',
                ]);

                $technicianStatus->update(['status' => 'busy']);
                broadcast(new TicketAssigned($ticket, Auth::user()->name))->toOthers();
            }
        }

        return redirect()->back()->with('info', 'Tu estado ha sido actualizado.');
    }

    public function selfAssign($id)
    {
        $ticket = Ticket::whereNull('technician_id')->where('id', $id)->firstOrFail();

        $ticket->update([
            'technician_id' => Auth::id(),
            'status' => 'open',
        ]);

        TechnicianStatus::updateOrCreate(
            ['user_id' => Auth::id()],
            ['status' => 'busy']
        );

        broadcast(new TicketAssigned($ticket, Auth::user()->name))->toOthers();

        return redirect()->back()->with('success', 'Te has asignado el ticket.');
    }
    public function create()
    {
        return view('tickets.create');
    }
}
