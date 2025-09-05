<?php
// app/Notifications/TicketStatusChanged.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\Ticket;

class TicketStatusChanged extends Notification
{
    use Queueable;

    public $ticket;
    public $oldStatus;
    public $newStatus;

    public function __construct(Ticket $ticket, string $oldStatus, string $newStatus)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        
        Log::info('TicketStatusChanged notification CONSTRUCTOR called:', [
            'ticket_id' => $ticket->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);
    }

    public function via($notifiable)
    {
        Log::info('TicketStatusChanged VIA method called for user:', [
            'user_id' => $notifiable->id,
            'user_email' => $notifiable->email
        ]);
        
        return ['mail']; // Solo mail por ahora
    }

    public function toMail($notifiable)
    {
        Log::info('TicketStatusChanged TOMAIL method called for user:', [
            'user_id' => $notifiable->id,
            'user_email' => $notifiable->email,
            'ticket_id' => $this->ticket->id
        ]);

        $statusLabels = [
            'pending' => 'Pendiente',
            'assigned' => 'Asignado',
            'open' => 'Abierto',
            'in_progress' => 'En Proceso',
            'resolved' => 'Resuelto',
            'closed' => 'Cerrado',
        ];

        $oldStatusLabel = $statusLabels[$this->oldStatus] ?? $this->oldStatus;
        $newStatusLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;

        $mailMessage = (new MailMessage)
                    ->subject("🔄 Cambio de Estado - Ticket #{$this->ticket->id}")
                    ->greeting("¡Hola {$notifiable->name}!")
                    ->line("El estado de tu ticket ha sido actualizado.")
                    ->line("**Detalles del cambio:**")
                    ->line("📋 **Ticket:** #{$this->ticket->id} - {$this->ticket->title}")
                    ->line("📊 **Estado anterior:** {$oldStatusLabel}")
                    ->line("✅ **Nuevo estado:** {$newStatusLabel}")
                    ->action('Ver Ticket', route('tickets.show', $this->ticket->id))
                    ->line('Te mantendremos informado de cualquier cambio adicional.')
                    ->salutation('Saludos, Sistema de Help Desk');

        Log::info('TicketStatusChanged mail message BUILT successfully');
        
        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'type' => 'status_changed',
            'message' => "Estado cambiado de {$this->oldStatus} a {$this->newStatus}"
        ];
    }
}
