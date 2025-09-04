<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    public $user;
    public $ticketId;

    public function __construct($user, $ticketId)
    {
        $this->user = $user;
        $this->ticketId = $ticketId;
    }

    public function broadcastOn()
    {
        return new Channel('ticket.' . $this->ticketId);
    }
}