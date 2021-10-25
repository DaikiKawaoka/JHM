require('./bootstrap');

import Siderbar from './components/SiderbarComponent.vue';

import { createApp } from 'vue';

const app = createApp({
    components: {
        Siderbar,
    }
}).mount('#app');