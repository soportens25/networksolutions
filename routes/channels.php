<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Ticket;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat.{ticketId}', function ($user, $ticketId) {
    // ⭐ VERIFICAR QUE EL USUARIO ESTÉ AUTENTICADO
    if (!$user) {
        \Log::warning('Usuario no autenticado para canal chat.' . $ticketId);
        return false;
    }
    
    \Log::info('Verificando acceso al chat', [
        'user_id' => $user->id,
        'ticket_id' => $ticketId
    ]);
    
    $ticket = Ticket::find($ticketId);
    
    if (!$ticket) {
        \Log::warning('Ticket no encontrado: ' . $ticketId);
        return false;
    }
    
    // ⭐ AUTORIZACIÓN SIMPLE
    $hasAccess = $user->id == $ticket->user_id || 
                 $user->id == $ticket->technician_id;
    
    \Log::info('Resultado autorización: ' . ($hasAccess ? 'PERMITIDO' : 'DENEGADO'));
    
    return $hasAccess;
}); 

// Canal de presencia de usuario (opcional)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
