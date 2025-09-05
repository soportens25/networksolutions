<?php
// app/Http/Controllers/ChatController.php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Ticket;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'content' => 'required|string|max:1000',
        ]);

        // Verificar acceso al ticket
        $ticket = Ticket::findOrFail($request->ticket_id);
        $this->authorize('access', $ticket);

        DB::beginTransaction();
        
        try {
            $message = Message::create([
                'ticket_id' => $request->ticket_id,
                'user_id' => Auth::id(),
                'content' => trim($request->content),
            ]);

            // Broadcasting a todos los usuarios del chat
            broadcast(new MessageSent($message));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mensaje enviado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje'
            ], 500);
        }
    }

    public function getMessages(Ticket $ticket)
    {
        $this->authorize('access', $ticket);

        $messages = Message::where('ticket_id', $ticket->id)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id'
        ]);

        $message = Message::findOrFail($request->message_id);
        
        if ($message->user_id !== Auth::id()) {
            $message->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
