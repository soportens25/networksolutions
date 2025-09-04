@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Panel de Técnico</h2>
    
    <h4>Estado Actual: <span x-text="status"></span></h4>
    <button class="btn btn-primary" @click="toggleStatus()">Cambiar Estado</button>

    <hr>
    <h4>Tickets Asignados</h4>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td><span class="badge bg-{{ $ticket->status == 'open' ? 'success' : 'secondary' }}">{{ $ticket->status }}</span></td>
                    <td>
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-info btn-sm">Ver</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('technicianPanel', () => ({
        status: 'available',
        async toggleStatus() {
            this.status = this.status === 'available' ? 'busy' : 'available';
            await fetch('/api/technician/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer {{ auth()->user()->api_token }}'
                },
                body: JSON.stringify({ status: this.status })
            });
        }
    }));
});
</script>
@endsection
