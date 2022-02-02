require('./bootstrap');

import Sidebar from './components/SidebarComponent.vue';
import DeleteModal from './components/DeleteModal.vue';
import ProgressComponent from './components/ProgressComponent.vue';
import CalendarComponent from './components/calendar/CalendarComponent.vue';
import HeadMessage from './components/HeadMessage.vue';
import StudentProfileComponent from './components/students/StudentProfileComponent.vue';
import CompaniesTeacherComponent from './components/CompaniesTeacherComponent.vue';
import EntryComponent from './components/entry/EntryComponent';
import CompanyShow from './components/company/show.vue';

import { createApp } from 'vue';

const app = createApp({
    components: {
        Sidebar, DeleteModal, CalendarComponent, StudentProfileComponent, HeadMessage, EntryComponent, CompanyShow, ProgressComponent, CompaniesTeacherComponent
    }
}).mount('#app');