<template>
  <nav
    class="navbar navbar-expand navbar-dark mb-3 border-bottom border-primary"
  >
    <div class="container">
      <RouterLink
        class="navbar-brand me-auto position-relative"
        z
        :to="{ name: 'home' }"
      >
        <img
          src="/android-chrome-192x192.png"
          height="32"
          width="32"
          alt="header icon"
        />
        <span class="position-absolute" style="left: 32px; top: 10px">
          erspiel
        </span>
      </RouterLink>
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a
            class="nav-link dropdown-toggle"
            href="#"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            :class="{ abcd: $route.name === 'browse' }"
          >
            Browse
          </a>
          <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1200">
            <li>
              <RouterLink
                class="dropdown-item"
                :to="{ name: 'browse', params: { type: 'novels' } }"
                active-class="active"
              >
                <i class="bi bi-book me-2"></i>
                Novels
              </RouterLink>
            </li>
            <li>
              <RouterLink
                class="dropdown-item"
                :to="{ name: 'browse', params: { type: 'chapters' } }"
                active-class="active"
              >
                <i class="bi bi-bookmark me-2"></i>
                Chapters
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
import { useSecurity } from '@/state/security';
import AppDropdown from '@/layout/components/AppDropdown.vue';

const { isAuthenticated } = useSecurity();
</script>
