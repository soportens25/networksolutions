<?php
// app/Services/TicketService.php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TechnicianStatus;
use App\Models\User;
use App\Events\TicketAssigned;
use App\Events\TicketStatusChanged;
use App\Notifications\TicketCreated;
use App\Notifications\TicketAssigned as TicketAssignedNotification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;

class TicketService
{
    public function getFilteredTickets($user, $filters = [])
    {
        $query = Ticket::with(['assignedUser', 'user.empresas', 'category'])
            ->latest();

        // Aplicar filtros por rol
        if ($user->hasRole('tecnico')) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('technician_id')
                    ->orWhere('technician_id', $user->id);
            });
        } elseif ($user->hasRole('empresarial')) {
            $empresaIds = $user->empresas()->pluck('empresas.id')->toArray();
            $query->whereHas('user.empresas', function ($q) use ($empresaIds) {
                $q->whereIn('empresas.id', $empresaIds);
            });
        }

        // Aplicar filtros adicionales
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['technician_id'])) {
            $query->where('technician_id', $filters['technician_id']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('id', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate(15)->appends($filters);
    }

    public function createTicket($data)
    {
        // Asignar prioridad por defecto si no se especifica
        $data['priority'] = $data['priority'] ?? 'medium';
        $data['user_id'] = auth()->id();

        // Buscar técnico disponible
        $technicianStatus = TechnicianStatus::where('status', 'available')
            ->whereHas('user', function ($q) {
                $q->role('tecnico');
            })
            ->first();

        if ($technicianStatus) {
            $data['technician_id'] = $technicianStatus->user_id;
            $data['status'] = 'assigned';
            $data['assigned_at'] = now();
        } else {
            $data['status'] = 'pending';
        }

        $ticket = Ticket::create($data);

        // Manejar archivos adjuntos
        if (isset($data['attachments'])) {
            $this->handleAttachments($ticket, $data['attachments']);
        }

        // Actualizar estado del técnico y enviar notificaciones
        if ($technicianStatus) {
            $technicianStatus->update(['status' => 'busy']);
            
            // Enviar notificación al técnico
            $technicianStatus->user->notify(new TicketAssignedNotification($ticket));
            
            // Broadcast del evento
            broadcast(new TicketAssigned($ticket, $technicianStatus->user->name));
        }

        // Notificar a supervisores sobre nuevo ticket
        $supervisors = User::role('supervisor')->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new TicketCreated($ticket));
        }

        return $ticket;
    }

    public function updateTicketStatus($ticket, $data)
    {
        $oldStatus = $ticket->status;
        $newStatus = $data['status'];

        $ticket->update([
            'status' => $newStatus,
            'resolved_at' => in_array($newStatus, ['resolved', 'closed']) ? now() : null,
        ]);

        // Liberar técnico si el ticket se cierra
        if (in_array($newStatus, ['resolved', 'closed']) && $ticket->technician_id) {
            $this->releaseTechnician($ticket->technician_id);
        }

        // Registrar actividad
        $ticket->activities()->create([
            'user_id' => auth()->id(),
            'action' => 'status_changed',
            'description' => "Estado cambiado de {$oldStatus} a {$newStatus}",
            'metadata' => json_encode(['old_status' => $oldStatus, 'new_status' => $newStatus])
        ]);

        // Enviar notificaciones
        broadcast(new TicketStatusChanged($ticket, $oldStatus, $newStatus));

        return [
            'success' => true,
            'message' => 'Estado del ticket actualizado correctamente.'
        ];
    }

    private function releaseTechnician($technicianId)
    {
        TechnicianStatus::where('user_id', $technicianId)
            ->update(['status' => 'available']);

        // Buscar ticket pendiente para asignar automáticamente
        $pendingTicket = Ticket::where('status', 'pending')->first();
        if ($pendingTicket) {
            $pendingTicket->update([
                'technician_id' => $technicianId,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

            TechnicianStatus::where('user_id', $technicianId)
                ->update(['status' => 'busy']);

            $technician = User::find($technicianId);
            broadcast(new TicketAssigned($pendingTicket, $technician->name));
        }
    }

    public function getTicketStats($user)
    {
        $query = Ticket::query();

        if ($user->hasRole('tecnico')) {
            $query->where('technician_id', $user->id);
        } elseif ($user->hasRole('empresarial')) {
            $empresaIds = $user->empresas()->pluck('empresas.id')->toArray();
            $query->whereHas('user.empresas', function ($q) use ($empresaIds) {
                $q->whereIn('empresas.id', $empresaIds);
            });
        }

        return [
            'total' => $query->count(),
            'pending' => $query->where('status', 'pending')->count(),
            'assigned' => $query->where('status', 'assigned')->count(),
            'in_progress' => $query->where('status', 'in_progress')->count(),
            'resolved' => $query->where('status', 'resolved')->count(),
            'closed' => $query->where('status', 'closed')->count(),
            'avg_resolution_time' => $this->getAverageResolutionTime($query),
        ];
    }

    private function handleAttachments($ticket, $attachments)
    {
        foreach ($attachments as $attachment) {
            $path = $attachment->store('ticket-attachments', 'public');
            
            $ticket->attachments()->create([
                'filename' => $attachment->getClientOriginalName(),
                'path' => $path,
                'size' => $attachment->getSize(),
                'mime_type' => $attachment->getMimeType(),
                'uploaded_by' => auth()->id(),
            ]);
        }
    }

    private function getAverageResolutionTime($query)
    {
        return $query->whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
            ->value('avg_hours') ?? 0;
    }

    public function exportTickets($user, $filters)
    {
        return Excel::download(new TicketsExport($user, $filters), 'tickets.xlsx');
    }
}
