require('./bootstrap');

import Sidebar from './components/SidebarComponent.vue';

import { createApp } from 'vue';

const app = createApp({
    components: {
        Sidebar,
    }
}).mount('#app');