<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Ticket #{{ $ticket->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="bg-orange-500 text-white p-2 rounded-lg">
                        <i class="ri-chat-3-line text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-semibold">Ticket #{{ $ticket->id }}</h1>
                        <p class="text-sm text-gray-500">{{ $ticket->title }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div id="connection-status" class="flex items-center space-x-2 text-sm">
                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                        <span>Conectando...</span>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg">
                        ← Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto p-4">

        <!-- Información del Ticket -->
        <div class="bg-white rounded-lg shadow border p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium">Creado por:</span>
                    <span class="ml-1">{{ $ticket->user->name }}</span>
                </div>
                <div>
                    <span class="font-medium">Técnico:</span>
                    <span class="ml-1">{{ $ticket->assignedUser->name ?? 'No asignado' }}</span>
                </div>
                <div>
                    <span class="font-medium">Estado:</span>
                    <span class="ml-1 px-2 py-1 text-xs rounded-full {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Chat -->
        <div class="bg-white rounded-lg shadow border overflow-hidden">

            <!-- Chat Header -->
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-medium">💬 Conversación</h3>
            </div>

            <!-- Messages -->
            <div id="chat-messages" class="h-96 overflow-y-auto p-6 space-y-4">
                @forelse ($messages as $message)
                    <div id="msg-{{ $message->id }}" class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-sm">
                            @if($message->user_id !== auth()->id())
                                <div class="flex items-center space-x-2 mb-1">
                                    <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-700">{{ substr($message->user->name, 0, 1) }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500 font-medium">{{ $message->user->name }}</span>
                                </div>
                            @endif

                            <div class="px-4 py-2 rounded-2xl {{ $message->user_id === auth()->id() ? 'bg-orange-500 text-white rounded-br-sm' : 'bg-gray-100 text-gray-900 rounded-bl-sm' }}">
                                <p class="text-sm">{{ $message->content }}</p>
                                <p class="text-xs mt-1 {{ $message->user_id === auth()->id() ? 'text-orange-200' : 'text-gray-500' }}">
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 empty-message">
                        <i class="ri-chat-3-line text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No hay mensajes aún. ¡Inicia la conversación!</p>
                    </div>
                @endforelse
            </div>

            <!-- Typing -->
            <div id="typing-indicator" class="px-6 py-2 text-sm text-gray-500 italic" style="display: none;">
                Escribiendo...
            </div>

            <!-- Input -->
            <div class="border-t p-4">
                <form id="message-form" class="flex items-end space-x-3">
                    <input type="hidden" id="ticket-id" value="{{ $ticket->id }}">
                    <textarea id="message-input" placeholder="Escribe tu mensaje..." rows="1" maxlength="1000" class="flex-1 px-4 py-2 border rounded-lg resize-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"></textarea>
                    <button type="submit" id="send-button" class="bg-orange-500 hover:bg-orange-600 disabled:bg-gray-300 text-white p-2 rounded-lg">
                        <i class="ri-send-plane-line"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.2.0/dist/web/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables globales
            const messagesContainer = document.getElementById('chat-messages');
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');
            const sendButton = document.getElementById('send-button');
            const connectionStatus = document.getElementById('connection-status');
            const typingIndicator = document.getElementById('typing-indicator');
            const ticketId = document.getElementById('ticket-id').value;
            const currentUserId = {{ auth()->id() }};
            const userName = @json(auth()->user()->name);

            let typingTimer = null;
            let channel = null;

            // Configurar Axios
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

            // Funciones auxiliares
            function updateStatus(text, color) {
                const colorClass = color === 'green' ? 'bg-green-400' : 'bg-red-400';
                connectionStatus.innerHTML = '<span class="w-2 h-2 ' + colorClass + ' rounded-full"></span><span>' + text + '</span>';
            }

            function scrollToBottom() {
                setTimeout(function() {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }, 100);
            }

            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) {
                    return map[m];
                });
            }

            // Función para mostrar notificación del navegador
            function showBrowserNotification(title, message) {
                if ('Notification' in window && Notification.permission === 'granted') {
                    try {
                        const notification = new Notification(title, {
                            body: message,
                            icon: '/favicon.ico',
                            badge: '/favicon.ico',
                            tag: 'chat-ticket-' + ticketId,
                            requireInteraction: false,
                            silent: false,
                            timestamp: Date.now()
                        });

                        // Click para enfocar pestaña
                        notification.onclick = function() {
                            window.focus();
                            notification.close();
                        };

                        // Auto-cerrar en 6 segundos
                        setTimeout(function() {
                            notification.close();
                        }, 6000);

                    } catch (error) {
                        console.error('Error creando notificación:', error);
                    }
                }
            }

            function addMessage(event) {
                if (document.getElementById('msg-' + event.id)) return;

                const isOwn = event.user.id === currentUserId;
                const messageDiv = document.createElement('div');
                messageDiv.id = 'msg-' + event.id;
                messageDiv.className = 'flex ' + (isOwn ? 'justify-end' : 'justify-start');

                const time = new Date(event.created_at).toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const avatarHtml = !isOwn ?
                    '<div class="flex items-center space-x-2 mb-1">' +
                    '<div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">' +
                    '<span class="text-xs font-medium text-gray-700">' + event.user.name.charAt(0) + '</span>' +
                    '</div>' +
                    '<span class="text-xs text-gray-500 font-medium">' + event.user.name + '</span>' +
                    '</div>' : '';

                const messageClass = isOwn ? 'bg-orange-500 text-white rounded-br-sm' : 'bg-gray-100 text-gray-900 rounded-bl-sm';
                const timeClass = isOwn ? 'text-orange-200' : 'text-gray-500';

                messageDiv.innerHTML =
                    '<div class="max-w-xs lg:max-w-sm">' +
                    avatarHtml +
                    '<div class="px-4 py-2 rounded-2xl ' + messageClass + '">' +
                    '<p class="text-sm">' + escapeHtml(event.content) + '</p>' +
                    '<p class="text-xs mt-1 ' + timeClass + '">' + time + '</p>' +
                    '</div>' +
                    '</div>';

                const emptyMessage = messagesContainer.querySelector('.empty-message');
                if (emptyMessage) emptyMessage.remove();

                messagesContainer.appendChild(messageDiv);
                scrollToBottom();

                // Notificación del navegador solo cuando la pestaña está oculta
                if (!isOwn && document.hidden) {
                    const notificationTitle = '💬 ' + event.user.name;
                    const notificationBody = event.content;
                    showBrowserNotification(notificationTitle, notificationBody);
                }
            }

            function showTyping(userName) {
                typingIndicator.textContent = userName + ' está escribiendo...';
                typingIndicator.style.display = 'block';
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    typingIndicator.style.display = 'none';
                }, 2000);
            }

            // Configuración de notificaciones
            function setupNotifications() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
            }

            // Configurar Echo
            try {
                window.Pusher = Pusher;
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: 'bd78fd19b532a3125db2',
                    cluster: 'us2',
                    forceTLS: true,
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                });

                // Estados de conexión
                window.Echo.connector.pusher.connection.bind('connected', function() {
                    updateStatus('Conectado', 'green');
                });

                window.Echo.connector.pusher.connection.bind('disconnected', function() {
                    updateStatus('Desconectado', 'red');
                });

                // Canal
                channel = window.Echo.private('chat.' + ticketId);

                channel.subscribed(function() {
                    updateStatus('Chat activo', 'green');
                });

                channel.error(function(error) {
                    updateStatus('Error de permisos', 'red');
                });

                // Listener para mensajes
                channel.listen('MessageSent', function(event) {
                    addMessage(event);
                });

                // Listener para typing
                channel.listenForWhisper('typing', function(e) {
                    if (e.userId !== currentUserId) {
                        showTyping(e.userName);
                    }
                });

            } catch (error) {
                console.error('Error inicializando Echo:', error);
                updateStatus('Error de configuración', 'red');
            }

            // Enviar mensaje
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const content = messageInput.value.trim();
                if (!content) return;

                sendButton.disabled = true;
                messageInput.disabled = true;

                axios.post('/chat/send', {
                    ticket_id: ticketId,
                    content: content
                })
                .then(function() {
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                })
                .catch(function(error) {
                    console.error('Error enviando mensaje:', error);
                    alert('Error al enviar mensaje');
                })
                .finally(function() {
                    sendButton.disabled = false;
                    messageInput.disabled = false;
                    messageInput.focus();
                });
            });

            // Auto-resize textarea y typing signal
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';

                if (channel && this.value.trim()) {
                    channel.whisper('typing', {
                        userId: currentUserId,
                        userName: userName
                    });
                }
            });

            // Enter para enviar
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    messageForm.dispatchEvent(new Event('submit'));
                }
            });

            // Inicializar notificaciones
            setupNotifications();

            // Scroll inicial
            scrollToBottom();
        });
    </script>
</body>

</html>
