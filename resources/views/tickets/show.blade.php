<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalle Ticket #{{ $ticket->id }}</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gradient-to-br from-orange-50 via-white to-stone-100 min-h-screen py-10">

    <!-- Header -->
    <div class="flex justify-between items-center mx-4 md:mx-20 mb-6">
        <div class="bg-orange-500 text-white py-4 px-6 shadow-md rounded-lg">
            <h1 class="text-xl font-bold flex items-center gap-2">
                <i class="ri-chat-3-line text-2xl"></i>
                Detalle del Ticket #{{ $ticket->id }}
            </h1>
        </div>
        <a href="{{ route('tickets.index') }}"
            class="bg-gray-800 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-700 transition">
            ← Volver
        </a>
    </div>

    <div class="container mx-auto px-4 py-8 space-y-8">
        <!-- Detalles -->
        <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">🎟 Información del Ticket</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <div><strong>Título:</strong> {{ $ticket->title }}</div>
                <div><strong>Técnico:</strong> {{ $ticket->assignedUser->name ?? 'No asignado' }}</div>
                <div><strong>Estado:</strong>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                        {{ $ticket->status == 'open' ? 'bg-green-100 text-green-700' :
                            ($ticket->status == 'in_progress' ? 'bg-yellow-100 text-yellow-700' :
                            ($ticket->status == 'resolved' ? 'bg-blue-100 text-blue-700' :
                            'bg-gray-200 text-gray-700')) }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </div>
                <div><strong>Descripción:</strong> {{ $ticket->description }}</div>
            </div>
        </div>

        <!-- Chat -->
        <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">💬 Chat en Tiempo Real</h3>
            </div>

            <!-- Mensajes -->
            <div id="chat-box" class="h-96 overflow-y-auto space-y-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                @foreach ($messages as $message)
                    <div id="msg-{{ $message->id }}"
                        class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-xs px-4 py-2 rounded-xl shadow-sm {{ $message->user_id === auth()->id() ? 'bg-orange-500 text-white' : 'bg-white text-gray-800 border border-gray-300' }}">
                            <p class="text-sm font-semibold mb-1">{{ $message->user->name }}</p>
                            <p class="text-sm">{{ $message->content }}</p>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $message->created_at->format('H:i') }}
                                @if ($message->user_id === auth()->id())
                                    <span id="status-{{ $message->id }}">
                                        {{ $message->read_at ? '✔✔ Leído' : '✔ Enviado' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Indicador escribiendo -->
            <div id="typing-indicator" class="text-sm italic text-gray-500 mt-2 hidden">
                Escribiendo...
            </div>

            <!-- Formulario -->
            <form id="chat-form" class="mt-4 flex items-center gap-3">
                <input type="hidden" id="ticket_id" value="{{ $ticket->id }}">
                <input type="hidden" id="user_id" value="{{ auth()->id() }}">
                <input type="text" id="message" placeholder="Escribe un mensaje..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-400 outline-none"
                    autocomplete="off">
                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg shadow transition">
                    Enviar
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@7.2.0/dist/web/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.1/echo.iife.min.js"></script>

    <script>
        const chatBox = document.getElementById('chat-box');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message');
        const ticketId = document.getElementById('ticket_id').value;
        const userId = document.getElementById('user_id').value;
        const typingIndicator = document.getElementById('typing-indicator');
        const userName = @json(auth()->user()->name);

        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!messageInput.value.trim()) return;

            axios.post('/Ns/Ns/public/api/messages', {
                ticket_id: ticketId,
                content: messageInput.value.trim()
            }).then(() => {
                messageInput.value = '';
            }).catch(error => {
                console.error("❌ Error al enviar mensaje:", error.response?.data || error);
            });
        });

        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: 'bd78fd19b532a3125db2',
            cluster: 'us2',
            forceTLS: true,
            authEndpoint: '/Ns/Ns/public/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }
        });

        const ticketChannel = window.Echo.private(`ticket.${ticketId}`);

        ticketChannel.listen('TicketMessageSent', (event) => {
            const exists = document.getElementById('msg-' + event.id);
            if (!exists) {
                const isOwn = event.user.id === parseInt(userId);
                const div = document.createElement('div');
                div.id = 'msg-' + event.id;
                div.className = 'flex ' + (isOwn ? 'justify-end' : 'justify-start');
                div.innerHTML = `
                    <div class="max-w-xs px-4 py-2 rounded-xl shadow-sm ${isOwn ? 'bg-orange-500 text-white' : 'bg-white text-gray-800 border border-gray-300'}">
                        <p class="text-sm font-semibold mb-1">${event.user.name}</p>
                        <p class="text-sm">${event.content}</p>
                        <div class="text-xs text-gray-400 mt-1">
                            ${new Date(event.created_at).toLocaleTimeString()}
                            ${isOwn ? `<span id="status-${event.id}">✔ Enviado</span>` : ''}
                        </div>
                    </div>`;
                chatBox.appendChild(div);
                chatBox.scrollTop = chatBox.scrollHeight;

                if (document.hidden && Notification.permission === "granted") {
                    new Notification("Nuevo mensaje", {
                        body: `${event.user.name}: ${event.content}`
                    });
                }
            }

            if (event.user.id != userId) {
                axios.post('/Ns/Ns/public/api/read', {
                    ticket_id: ticketId,
                    message_id: event.id
                });
            }
        });

        let typingTimeout;
        messageInput.addEventListener('input', () => {
            ticketChannel.whisper('typing', {
                user_id: userId,
                name: userName
            });
        });

        ticketChannel.listenForWhisper('typing', (e) => {
            if (e.user_id != userId) {
                typingIndicator.textContent = `${e.name} está escribiendo...`;
                typingIndicator.classList.remove('hidden');
                clearTimeout(window.typingTimeout);
                window.typingTimeout = setTimeout(() => {
                    typingIndicator.classList.add('hidden');
                }, 1200);
            }
        });

        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }
    </script>
</body>

</html>
