import { createApp } from 'vue';
import '@/styles/app.scss';
import 'bootstrap';
import AppLayout from '@/layout/AppLayout.vue';
import router from '@/router';
import 'bootstrap-icons/font/bootstrap-icons.css';
import { createHead } from '@vueuse/head';
import axios from 'axios';
import { useToast } from '@/utils/notification.js';

createApp(AppLayout).use(router).use(createHead()).mount('#app');

const { error } = useToast();

axios.interceptors.response.use(
  (response) => response,
  (e) => {
    if (e.response.status === 404 && e.response.config.method === 'get') {
      router.replace({ name: 'not-found' });
    } else if (e.response.status === 422) {
      error('Validation error');
    } else if (e.response.data.message) {
      error(e.response.data.message);
    }
    return Promise.reject(e);
  }
);
