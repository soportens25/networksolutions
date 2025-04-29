<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\TicketMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        $message->load('user'); // Asegura que la relación 'user' esté cargada

        // Lanza el evento de broadcasting SOLO una vez, después de guardar el mensaje
        broadcast(new TicketMessageSent($message))->toOthers();

        // Opcional: puedes registrar en el log si lo deseas
        \Log::info('Broadcasting TicketMessageSent', ['message_id' => $message->id]);
        broadcast(new TicketMessageSent($message));
        
        // Retorna la respuesta JSON del mensaje creado
        return response()->json($message);
    }
}
