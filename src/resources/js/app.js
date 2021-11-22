require('./bootstrap');

import Sidebar from './components/SidebarComponent.vue';
import DeleteModal from './components/DeleteModal.vue';
import CalendarComponent from './components/CalendarComponent.vue';
import StudentProfileComponent from './components/students/StudentProfileComponent.vue';

import { createApp } from 'vue';

const app = createApp({
    components: {
        Sidebar, DeleteModal, CalendarComponent, StudentProfileComponent
    }
}).mount('#app');