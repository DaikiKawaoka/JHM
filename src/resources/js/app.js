require('./bootstrap');

import Sidebar from './components/SidebarComponent.vue';
import DeleteModal from './components/DeleteModal.vue';
import CalendarComponent from './components/calendar/CalendarComponent.vue';
import HeadMessage from './components/HeadMessage.vue';
import StudentProfileComponent from './components/students/StudentProfileComponent.vue';
import CompaniesTeacherComponent from './components/CompaniesTeacherComponent.vue';

import { createApp } from 'vue';

const app = createApp({
    components: {
        Sidebar, DeleteModal, CalendarComponent, StudentProfileComponent, HeadMessage, CompaniesTeacherComponent
    }
}).mount('#app');