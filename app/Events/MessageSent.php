<?php
// app/Events/MessageSent.php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->ticket_id);
    }

    // ⭐ SIN broadcastAs() - usa el nombre de clase
    
    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'ticket_id' => $this->message->ticket_id,
            'created_at' => $this->message->created_at->toISOString(),
            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
            ]
        ];
    }
}
