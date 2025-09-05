<?php
// app/Notifications/TicketAssigned.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketAssigned extends Notification
{
    use Queueable;

    protected $ticket;
    protected $notification_type;

    public function __construct(Ticket $ticket, $notification_type = 'technician')
    {
        $this->ticket = $ticket;
        $this->notification_type = $notification_type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        switch ($this->notification_type) {
            case 'admin_notification':
                return $this->adminNotification($notifiable);
            case 'creator_notification':
                return $this->creatorNotification($notifiable);
            default:
                return $this->technicianNotification($notifiable);
        }
    }

    private function technicianNotification($notifiable)
    {
        return (new MailMessage)
                    ->subject('🔧 Nuevo Ticket Asignado - #' . $this->ticket->id)
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('Se te ha asignado un nuevo ticket.')
                    ->line('**Detalles del ticket:**')
                    ->line('📋 **ID:** #' . $this->ticket->id)
                    ->line('📝 **Título:** ' . $this->ticket->title)
                    ->line('👤 **Creado por:** ' . $this->ticket->user->name)
                    ->line('📅 **Fecha:** ' . $this->ticket->created_at->format('d/m/Y H:i'))
                    ->line('⚠️ **Descripción:** ' . $this->ticket->description)
                    ->action('Atender Ticket', route('tickets.show', $this->ticket->id))
                    ->line('Por favor, atiende este ticket lo antes posible.')
                    ->salutation('¡Éxito en tu trabajo!');
    }

    private function adminNotification($notifiable)
    {
        return (new MailMessage)
                    ->subject('📋 Ticket Asignado - #' . $this->ticket->id)
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('El ticket #' . $this->ticket->id . ' ha sido asignado.')
                    ->line('**Detalles:**')
                    ->line('👤 **Técnico asignado:** ' . $this->ticket->assignedUser->name)
                    ->line('📝 **Título:** ' . $this->ticket->title)
                    ->line('👤 **Creado por:** ' . $this->ticket->user->name)
                    ->action('Ver Ticket', route('tickets.show', $this->ticket->id))
                    ->salutation('Sistema de Help Desk');
    }

    private function creatorNotification($notifiable)
    {
        return (new MailMessage)
                    ->subject('✅ Tu Ticket ha sido Asignado - #' . $this->ticket->id)
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('Tu ticket ha sido asignado a un técnico.')
                    ->line('**Detalles:**')
                    ->line('📋 **ID:** #' . $this->ticket->id)
                    ->line('👨‍💻 **Técnico asignado:** ' . $this->ticket->assignedUser->name)
                    ->line('📝 **Título:** ' . $this->ticket->title)
                    ->action('Ver Ticket', route('tickets.show', $this->ticket->id))
                    ->line('El técnico comenzará a trabajar en tu solicitud pronto.')
                    ->salutation('Gracias por tu paciencia');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'technician' => $this->ticket->assignedUser->name,
            'type' => 'ticket_assigned',
            'message' => 'Ticket #' . $this->ticket->id . ' asignado a ' . $this->ticket->assignedUser->name
        ];
    }
}
