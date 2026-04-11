import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { Peer } from 'peerjs';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Initialize PeerJS
if (typeof userId !== 'undefined') {
    window.peer = new Peer(`linkup-user-${userId}`, {
        host: '/',
        port: 9000, // Default PeerJS server or use their cloud
        path: '/peerjs',
        debug: 3
    });

    // Fallback to PeerJS Cloud if local server isn't running
    // window.peer = new Peer(`linkup-user-${userId}`);

    window.peer.on('open', (id) => {
        console.log('My peer ID is: ' + id);
    });

    window.peer.on('error', (err) => {
        console.error('PeerJS error:', err);
        // If it fails to connect to local, try cloud as fallback
        if (err.type === 'server-error' || err.type === 'socket-error') {
            console.log('Attempting to connect to PeerJS Cloud...');
            window.peer = new Peer(`linkup-user-${userId}`);
        }
    });
}
