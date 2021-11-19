require('./bootstrap');

import Sidebar from './components/SidebarComponent.vue';
import DeleteModal from './components/DeleteModal.vue';
import CalendarComponent from './components/CalendarComponent.vue';
import ProgressComponent from './components/ProgressComponent.vue';

import { createApp } from 'vue';

const app = createApp({
    components: {
        Sidebar, DeleteModal, CalendarComponent,ProgressComponent
    }
}).mount('#app');