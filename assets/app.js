import { createApp } from 'vue';
import '@/styles/app.scss';
import 'bootstrap';
import AppLayout from '@/layout/AppLayout.vue';
import router from '@/router/router';
import 'bootstrap-icons/font/bootstrap-icons.css';

createApp(AppLayout).use(router).mount('#app');
