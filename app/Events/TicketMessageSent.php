<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TicketMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;

        Log::info('TicketMessageSent evento creado', [
            'message_id' => $message->id,
            'ticket_id' => $message->ticket_id,
            'user_id' => $message->user_id
        ]);
    }

    public function broadcastOn()
    {
        $channel = 'ticket.' . $this->message->ticket_id;
        Log::info('Broadcasting en canal: ' . $channel);
        return new PrivateChannel($channel);
    }

    public function broadcastAs()
    {
        return 'TicketMessageSent';
    }

    public function broadcastWith()
    {
        $data = [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'ticket_id' => $this->message->ticket_id,
            'created_at' => $this->message->created_at->toISOString(),
            'read_at' => $this->message->read_at ? $this->message->read_at->toISOString() : null,
            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
                'email' => $this->message->user->email,
            ]
        ];

        Log::info('Datos del broadcast enviados:', $data);
        return $data;
    }

    public function shouldBroadcast()
    {
        return !empty($this->message->content);
    }
}
