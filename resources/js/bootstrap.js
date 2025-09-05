import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Configurar Axios globalmente
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configurar token CSRF
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Configurar Pusher y Laravel Echo
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY || 'bd78fd19b532a3125db2',
    cluster: process.env.MIX_PUSHER_APP_CLUSTER || 'us2',
    forceTLS: true,
    namespace: '', // ⭐ IMPORTANTE: Namespace vacío
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': token ? token.content : '',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }
});

// Debug de conexión (opcional - solo para desarrollo)
if (process.env.NODE_ENV === 'development') {
    window.Echo.connector.pusher.connection.bind('connected', () => {
        console.log('✅ Laravel Echo conectado exitosamente');
    });

    window.Echo.connector.pusher.connection.bind('disconnected', () => {
        console.warn('❌ Laravel Echo desconectado');
    });

    window.Echo.connector.pusher.connection.bind('error', (error) => {
        console.error('❌ Error en Laravel Echo:', error);
    });
}
