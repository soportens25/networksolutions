import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'bd78fd19b532a3125db2', // Tu PUSHER_APP_KEY
    cluster: 'us2',             // Tu PUSHER_APP_CLUSTER
    forceTLS: true,
    // Si tu app está en /Ns/Ns/public, agrega el endpoint:
    authEndpoint: '/Ns/Ns/public/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }
});


// Para depuración, muestra eventos de conexión
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Conexión WebSocket establecida.');
});
window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.warn('Conexión WebSocket desconectada. Se intentará reconectar...');
});
window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('Error en la conexión WebSocket:', err);
});

// Suscribirse al canal del ticket y escuchar el evento 'MessageSent'
const ticketChannel = window.Echo.private(`ticket.${ticketId}`)
    .listen('TicketMessageSent', (event) => {
        console.log('Nuevo mensaje recibido:', event);
        // Actualiza el DOM según tus necesidades, por ejemplo:
        const chatBox = document.getElementById('chat-box');
        if (!document.getElementById('msg-' + event.id)) {
            let newMessage = document.createElement('div');
            newMessage.id = 'msg-' + event.id;
            newMessage.innerHTML = `<strong>${event.user}:</strong> ${event.content} <small>${event.created_at}</small>`;
            chatBox.appendChild(newMessage);
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });

let typingTimeout;
messageInput.addEventListener('input', () => {
    axios.post('/api/typing', {
        ticket_id: ticketId
    });

    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        // Podrías emitir un evento para "dejó de escribir"
    }, 3000);
});

if (document.hidden) {
    if (Notification.permission === "granted") {
        new Notification("Nuevo mensaje", {
            body: `${event.user.name}: ${event.content}`
        });
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission();
    }
}
