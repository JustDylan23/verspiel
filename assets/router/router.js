import { createRouter, createWebHistory } from 'vue-router';
import useSecurity from '@/state/security';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/home/HomeView.vue'),
    },
    {
      path: '/novel/:id(\\d+)',
      name: 'novel',
      component: () => import('@/views/novel/NovelView.vue'),
    },
    {
      path: '/chapter/:id(\\d+)',
      name: 'chapter',
      component: () => import('@/views/chapter/ChapterView.vue'),
    },
    {
      path: '/browse/:type(novels|chapters)',
      name: 'browse',
      component: () => import('@/views/browse/BrowseView.vue'),
    },
  ],
});

export default router;

const { isAuthenticated, hasSession, refreshUser } = useSecurity();

router.beforeEach(async (to, from, next) => {
  if (!isAuthenticated.value && hasSession()) {
    await refreshUser();
  }
  next();
});
