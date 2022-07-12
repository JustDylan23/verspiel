<template>
  <nav
    class="navbar navbar-expand navbar-dark mb-3 border-bottom border-primary"
  >
    <div class="container">
      <button
        class="btn d-block d-sm-none rounded-3 me-2"
        data-bs-toggle="offcanvas"
        data-bs-target="#leftOffcanvas"
        type="button"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
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
        <span
          class="position-absolute"
          style="left: 32px; top: 6px; font-size: 25px"
        >
          erspiel
        </span>
      </RouterLink>
      <ul class="navbar-nav">
        <AppBrowseDropdown />
        <li class="nav-item dropdown d-none d-sm-block">
          <AppAuthenticatedDropdown v-if="isAuthenticated" />
          <AppAnonymousDropdown v-else />
        </li>
        <li class="nav-item dropdown d-block d-sm-none">
          <a
            class="nav-link py-0 d-block d-sm-none rounded-3 me-2"
            data-bs-toggle="offcanvas"
            data-bs-target="#rightOffcanvas"
            type="button"
          >
            <span class="fs-1 bi-person-circle"></span>
          </a>
        </li>
      </ul>
    </div>
  </nav>
  <LeftOffcanvas />
  <RightOffcanvas />
</template>

<script setup>
import AppAnonymousDropdown from '@/layout/components/AppAnonymousDropdown.vue';
import { useSecurity } from '@/state/security';
import AppAuthenticatedDropdown from '@/layout/components/AppAuthenticatedDropdown.vue';
import LeftOffcanvas from '@/layout/components/mobile/LeftOffcanvas.vue';
import RightOffcanvas from '@/layout/components/mobile/RightOffcanvas.vue';
import AppBrowseDropdown from '@/layout/components/AppBrowseDropdown.vue';

const { isAuthenticated } = useSecurity();
</script>
