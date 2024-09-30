import './assets/css/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
const app = createApp(App)
app.use(router)
app.component('VueDatePicker', VueDatePicker)
app.mount('#app')

console.log(import.meta.env.VITE_PUSHER_APP_KEY);
console.log(import.meta.env.VITE_PUSHER_APP_CLUSTER);


const echo = new Echo({
    broadcaster: 'pusher',
    key: '1b9ddaf49da057b45b47',
    cluster: 'eu',
    encrypted: true
});

