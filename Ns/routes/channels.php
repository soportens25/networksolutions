<?php

use Illuminate\Support\Facades\Broadcast;

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
Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    // Para pruebas, puedes poner temporalmente:
    return true;
    // O la lógica real:
    // return $user->tickets()->where('id', $ticketId)->exists();
});
    

// Canal de presencia de usuario (opcional)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});