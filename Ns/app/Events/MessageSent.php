<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        // Emite en un canal privado o público según tu configuración
        return ['ticket.' . $this->message->ticket_id];
    }

    public function broadcastWith()
    {
        return [
            'id'         => $this->message->id,
            'content'    => $this->message->content,
            'user'       => $this->message->user->name,
            'created_at' => $this->message->created_at->format('H:i'),
        ];
    }
    
}
