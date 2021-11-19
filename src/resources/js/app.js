require('./bootstrap');

import Sidebar from './components/SidebarComponent.vue';
import DeleteModal from './components/DeleteModal.vue';
import CalendarComponent from './components/CalendarComponent.vue';
import HeadMessage from './components/HeadMessage.vue';

import { createApp } from 'vue';

const app = createApp({
    components: {
        Sidebar, DeleteModal, CalendarComponent, HeadMessage
    }
}).mount('#app');