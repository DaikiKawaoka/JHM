require('./bootstrap');

import Sidebar from './components/SidebarComponent.vue';
import DeleteModal from './components/DeleteModal.vue';
import CalendarComponent from './components/calendar/CalendarComponent.vue';
import HeadMessage from './components/HeadMessage.vue';
import StudentProfileComponent from './components/students/StudentProfileComponent.vue';
import CompanyCreate from './components/company/create.vue';
import EntryComponent from './components/entry/EntryComponent';

import { createApp } from 'vue';

const app = createApp({
    components: {
        Sidebar, DeleteModal, CalendarComponent, StudentProfileComponent, HeadMessage, CompanyCreate, EntryComponent,
    }
}).mount('#app');