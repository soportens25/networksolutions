<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\TicketMessageSent;


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

        $message->load('user'); // Cargamos relación para que el evento lo tenga

        broadcast(new TicketMessageSent($message));
        
        return response()->json($message);

        event(new \App\Events\TicketMessageSent($message)); // adicional al broadcast

        \Log::info('Evento TicketMessageSent lanzado:', ['message_id' => $message->id]);
    }
}
