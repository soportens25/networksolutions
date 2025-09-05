<?php
// app/Http/Controllers/MessageController.php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Ticket;
use App\Events\TicketMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'content' => 'required|string|max:1000',
        ]);

        // Verificar acceso al ticket
        $ticket = Ticket::findOrFail($request->ticket_id);
        $user = Auth::user();
        
        if (!$user->hasRole('admin') && $ticket->user_id !== $user->id && $ticket->technician_id !== $user->id) {
            return response()->json(['error' => 'No tienes acceso a este ticket'], 403);
        }

        try {
            $message = Message::create([
                'ticket_id' => $request->ticket_id,
                'user_id' => $user->id,
                'content' => $request->content,
            ]);

            // ⭐ IMPORTANTE: Cargar relación user para el broadcasting
            $message->load('user');

            // ⭐ BROADCASTING SIN .toOthers() - Enviar a TODOS los clientes
            broadcast(new TicketMessageSent($message));

            Log::info('Mensaje enviado y broadcast realizado', [
                'message_id' => $message->id,
                'ticket_id' => $message->ticket_id,
                'user_id' => $user->id
            ]);

            // ⭐ RETORNAR ESTRUCTURA PARA EL FRONTEND (SIN USAR PARA ACTUALIZAR UI)
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'created_at' => $message->created_at->toISOString(),
                    'user_id' => $message->user_id,
                    'ticket_id' => $message->ticket_id
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error enviando mensaje:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ticket_id' => $request->ticket_id,
                'user_id' => $user->id
            ]);

            return response()->json([
                'error' => 'Error al enviar mensaje. Intenta de nuevo.',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        try {
            $message = Message::findOrFail($request->message_id);
            
            // Solo marcar como leído si no es el autor del mensaje
            if ($message->user_id !== Auth::id()) {
                $message->update(['read_at' => now()]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Error marcando mensaje como leído:', [
                'error' => $e->getMessage(),
                'message_id' => $request->message_id,
                'user_id' => Auth::id()
            ]);

            return response()->json(['error' => 'Error al marcar como leído'], 500);
        }
    }
}
