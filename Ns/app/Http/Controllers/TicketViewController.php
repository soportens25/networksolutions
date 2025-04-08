<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketViewController extends Controller {
    public function index() {
        $tickets = Ticket::where('user_id', Auth::id())->get();
        return view('tickets.index', compact('tickets'));
    }

    public function show($id) {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    public function create() {
        return view('tickets.create');
    }

    public function technicianPanel() {
        $tickets = Ticket::where('technician_id', Auth::id())->get();
        return view('technician.panel', compact('tickets'));
    }
}
