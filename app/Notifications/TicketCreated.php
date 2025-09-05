<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class TicketCreated extends Notification
{
    use Queueable;

    protected $ticket;
    protected $recipient_type;

    public function __construct(Ticket $ticket, $recipient_type = 'admin')
    {
        $this->ticket = $ticket;
        $this->recipient_type = $recipient_type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        if ($this->recipient_type === 'admin') {
            return $this->adminEmail($notifiable);
        } else {
            return $this->creatorEmail($notifiable);
        }
    }

    private function adminEmail($notifiable)
    {
        return (new MailMessage)
                    ->subject('🎫 Nuevo Ticket Creado - #' . $this->ticket->id)
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('Se ha creado un nuevo ticket en el sistema.')
                    ->line('**Detalles del ticket:**')
                    ->line('📋 **ID:** #' . $this->ticket->id)
                    ->line('📝 **Título:** ' . $this->ticket->title)
                    ->line('👤 **Creado por:** ' . $this->ticket->user->name)
                    ->line('📅 **Fecha:** ' . $this->ticket->created_at->format('d/m/Y H:i'))
                    ->line('🔄 **Estado:** ' . ucfirst($this->ticket->status))
                    ->action('Ver Ticket', route('tickets.show', $this->ticket->id))
                    ->line('Como administrador, puedes asignar este ticket a un técnico disponible.')
                    ->salutation('Saludos, Sistema de Help Desk');
    }

    private function creatorEmail($notifiable)
    {
        return (new MailMessage)
                    ->subject('✅ Ticket Creado Exitosamente - #' . $this->ticket->id)
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('Tu ticket ha sido creado exitosamente.')
                    ->line('**Detalles de tu ticket:**')
                    ->line('📋 **ID:** #' . $this->ticket->id)
                    ->line('📝 **Título:** ' . $this->ticket->title)
                    ->line('📅 **Fecha:** ' . $this->ticket->created_at->format('d/m/Y H:i'))
                    ->line('🔄 **Estado:** ' . ucfirst($this->ticket->status))
                    ->action('Ver Ticket', route('tickets.show', $this->ticket->id))
                    ->line('Te notificaremos cuando haya actualizaciones en tu ticket.')
                    ->salutation('Gracias por confiar en nosotros');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'type' => 'ticket_created',
            'message' => 'Nuevo ticket creado: #' . $this->ticket->id
        ];
    }
}
