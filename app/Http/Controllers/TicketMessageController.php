<?php

namespace App\Http\Controllers;

use App\Events\TicketMessageSent;
use App\Models\TicketMessage;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth,Storage;

class TicketMessageController extends Controller {
    public function sendMessage(Request $request, $id) {
        $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048'
        ]);
    
        $ticket = Ticket::findOrFail($id);
        $sender = Auth::user()->name;
        $attachmentPath = null;
    
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ticket_attachments', 'public');
        }
    
        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'status' => 'enviado',
        ]);
    
        broadcast(new TicketMessageSent($message))->toOthers();
    
        return response()->json([
            'message' => $request->message,
            'sender' => $sender,
            'attachment' => $attachmentPath ? Storage::url($attachmentPath) : null,
            'status' => 'enviado'
        ]);
    }
    public function markAsRead($ticketId) {
        TicketMessage::where('ticket_id', $ticketId)
            ->where('user_id', '!=', auth()->id())
            ->update(['status' => 'leído']);
    
        return response()->json(['message' => 'Mensajes marcados como leídos']);
    }   
}
