<template>
  <div id="leftOffcanvas" class="offcanvas offcanvas-start" tabindex="-1">
    <div class="offcanvas-header">
      <RouterLink
        class="offcanvas-title fs-5 text-white position-relative d-flex align-items-center"
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
          style="left: 32px; top: -2px; font-size: 25px"
        >
          erspiel
        </span>
      </RouterLink>
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="offcanvas"
      ></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <div class="flex-grow-1 d-flex flex-column gap-4">
        <section>
          <h4>Browse</h4>
          <RouterLink
            v-slot="{ navigate, isActive }"
            :to="{ name: 'browse', params: { type: 'chapters' } }"
          >
            <button
              class="btn w-100 text-start"
              :class="isActive ? 'btn-primary' : 'btn-dark-grey'"
              @click="navigate"
            >
              <i class="bi bi-book me-2"></i>
              Novels
            </button>
          </RouterLink>
          <RouterLink
            v-slot="{ navigate, isActive }"
            :to="{ name: 'browse', params: { type: 'novels' } }"
          >
            <button
              class="btn w-100 text-start"
              :class="isActive ? 'btn-primary' : 'btn-dark-grey'"
              @click="navigate"
            >
              <i class="bi bi-bookmark me-2"></i>
              Chapters
            </button>
          </RouterLink>
        </section>
        <section
          v-if="
            isAuthenticated &&
            (user.badges.includes('admin') || user.badges.includes('editor'))
          "
        >
          <h4>Admin</h4>
          <a href="/admin/dashboard" target="_blank">
            <button
              class="btn w-100 text-start btn-dark-grey"
              @click="navigate"
            >
              <i class="bi bi-person-lines-fill me-2"></i>
              Admin
            </button>
          </a>
        </section>
      </div>
      <footer class="d-flex justify-content-between align-items-center">
        <div>{{ new Date().getFullYear() }} &copy; Verspiel</div>
        <a href="https://discord.gg/8URgeTUumB">
          <button
            class="fs-4 btn w-100 btn-dark-grey bi bi-discord me-2"
            @click="navigate"
          ></button>
        </a>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { useSecurity } from '@/state/security.js';
const { isAuthenticated, user } = useSecurity();
</script>
