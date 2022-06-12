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
        v-if="user.badges.includes('Admin') || user.badges.includes('Editor')"
        class="dropdown-item"
        href="/admin/dashboard"
      >
        <i class="bi bi-person-lines-fill me-2" />
        Admin
      </a>
    </li>
    <li>
      <hr class="dropdown-divider" />
    </li>
    <li>
      <a class="dropdown-item" href="#" @click="logout">
        <i class="bi bi-box-arrow-right me-2" />
        Logout
      </a>
    </li>
  </ul>
</template>

<script setup>
import { useSecurity } from '@/state/security';

const { logout, user } = useSecurity();
</script>
