<template>
  <a
    class="nav-link dropdown-toggle"
    href="#"
    role="button"
    data-bs-toggle="dropdown"
    aria-expanded="false"
  >
    {{ user.username }}
  </a>
  <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1200">
    <li>
      <RouterLink
        class="dropdown-item"
        :to="{ name: 'profile' }"
        active-class="active"
      >
        <i class="bi bi-person-circle me-2" />
        Profile
      </RouterLink>
    </li>
    <li>
      <a
        v-if="user.badges.includes('admin') || user.badges.includes('editor')"
        class="dropdown-item"
        href="/admin/dashboard"
        target="_blank"
      >
        <i class="bi bi-person-lines-fill me-2" />
        Admin
      </a>
    </li>
    <li>
      <hr class="dropdown-divider" />
    </li>
    <li>
      <a class="dropdown-item" href="#" @click="logoutApp()">
        <i class="bi bi-box-arrow-right me-2" />
        Logout
      </a>
    </li>
  </ul>
</template>

<script setup>
import { useSecurity } from '@/state/security';
import { useRoute, useRouter } from 'vue-router';

const { logout, user } = useSecurity();

const route = useRoute();
const router = useRouter();

const logoutApp = () => {
  if (route?.meta?.auth === true) {
    router.push({ name: 'home' });
  }
  logout();
};
</script>
