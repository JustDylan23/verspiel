<template>
  <nav
    class="navbar navbar-expand navbar-dark mb-3 border-bottom border-primary"
  >
    <div class="container">
      <RouterLink class="navbar-brand me-auto" :to="{ name: 'home' }">
        <div
          style="width: 30px; height: 24px"
          class="d-inline-block align-text-top bg-primary rounded-3"
        />
        Verspiel
      </RouterLink>
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a
            id="navbarDropdown"
            class="nav-link dropdown-toggle"
            href="#"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            Browse
          </a>
          <ul
            class="dropdown-menu dropdown-menu-end"
            aria-labelledby="navbarDropdown"
            style="z-index: 1200"
          >
            <li>
              <RouterLink
                class="dropdown-item"
                :to="{ name: 'browse', params: { type: 'novels' } }"
                active-class="active"
              >
                <i class="bi bi-book me-2"></i> Novels
              </RouterLink>
            </li>
            <li>
              <RouterLink
                class="dropdown-item"
                :to="{ name: 'browse', params: { type: 'chapters' } }"
                active-class="active"
              >
                <i class="bi bi-bookmark me-2"></i> Chapters
              </RouterLink>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <AppDropdown v-if="isAuthenticated" />
          <AppLogin v-else />
        </li>
      </ul>
    </div>
  </nav>
</template>

<script setup>
import AppLogin from '@/layout/components/AppLogin.vue';
import useSecurity from '@/state/security';
import AppDropdown from '@/layout/components/AppDropdown.vue';

const { isAuthenticated } = useSecurity();
</script>
