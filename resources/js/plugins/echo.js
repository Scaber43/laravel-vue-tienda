import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

const echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: Number(import.meta.env.VITE_REVERB_PORT),
    wssPort: Number(import.meta.env.VITE_REVERB_PORT),
    forceTLS: false,
    enabledTransports: ['ws'],
    authEndpoint: '/api/v1/broadcasting/auth',
    auth: {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
        }
    }
})

window.Echo = echo

export default echo
