<?php
// app/Policies/TicketPolicy.php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function access(User $user, Ticket $ticket)
    {
        return $user->hasRole('admin') || 
               $ticket->user_id === $user->id || 
               $ticket->technician_id === $user->id;
    }
}
