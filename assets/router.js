import { createRouter, createWebHistory } from 'vue-router';
import { useSecurity } from '@/state/security';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/home/HomeView.vue'),
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/views/register/RegisterView.vue'),
    },
    {
      path: '/registration/completed',
      name: 'registration-completed',
      component: () => import('@/views/register/RegistrationCompletedView.vue'),
      meta: {
        auth: false,
      },
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('@/views/profile/ProfileView.vue'),
      meta: {
        auth: true,
      },
    },
    {
      path: '/password-reset/request',
      name: 'password-reset-request',
      component: () =>
        import('@/views/password-reset/PasswordResetRequestView.vue'),
    },
    {
      path: '/password-reset/complete/:token',
      name: 'password-reset-complete',
      component: () =>
        import('@/views/password-reset/PasswordResetCompleteView.vue'),
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
    {
      path: '/access-denied',
      name: 'access-denied',
      component: () => import('@/views/error/AccessDeniedView.vue'),
    },
    {
      path: '/not-found',
      name: 'not-found',
      component: () => import('@/views/error/NotFoundView.vue'),
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found-fallback',
      component: () => import('@/views/error/NotFoundView.vue'),
    },
  ],
});

export default router;

const { isAuthenticated, hasSession, refreshUser } = useSecurity();

router.beforeEach(async (to, from, next) => {
  if (!isAuthenticated.value && hasSession()) {
    await refreshUser();
  }
  if (
    (to.meta.auth && !isAuthenticated.value) ||
    (to.meta.auth === false && isAuthenticated.value)
  ) {
    next({ name: 'access-denied' });
  } else {
    next();
  }
});
