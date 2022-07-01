<template>
  <div id="rightOffcanvas" class="offcanvas offcanvas-end" tabindex="-1">
    <div class="offcanvas-header position-fixed">
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="offcanvas"
      ></button>
    </div>
    <div class="offcanvas-body">
      <div
        class="d-flex flex-column text-center border-bottom border-primary pb-3 mb-4"
      >
        <i class="bi bi-person" style="font-size: 3rem"></i>
        <h4 v-if="isAuthenticated" class="text-truncate">
          {{ user.username }}
        </h4>
        <h4 v-else>Guest</h4>
        <div
          v-if="isAuthenticated"
          class="d-flex flex-wrap gap-2 justify-content-center"
        >
          <div
            v-for="(badge, key) in user.badges"
            :key="key"
            class="badge text-bg-primary rounded-pill text-capitalize"
          >
            {{ badge }}
          </div>
        </div>
      </div>
      <div v-if="isAuthenticated" class="row gx-2">
        <div class="col">
          <RouterLink v-slot="{ navigate, isActive }" :to="{ name: 'profile' }">
            <button
              class="btn w-100"
              data-bs-dismiss="offcanvas"
              :class="isActive ? 'btn-primary' : 'btn-light-grey'"
              @click="navigate"
            >
              Profile
            </button>
          </RouterLink>
        </div>
        <div class="col">
          <button
            class="btn w-100"
            data-bs-dismiss="offcanvas"
            :class="isActive ? 'btn-primary' : 'btn-light-grey'"
            @click="logoutApp()"
          >
            Logout
          </button>
        </div>
      </div>
      <div v-else class="row gx-2">
        <div class="col">
          <RouterLink v-slot="{ navigate, isActive }" :to="{ name: 'login' }">
            <button
              class="btn w-100"
              data-bs-dismiss="offcanvas"
              :class="isActive ? 'btn-primary' : 'btn-light-grey'"
              @click="navigate"
            >
              Log In
            </button>
          </RouterLink>
        </div>
        <div class="col">
          <RouterLink
            v-slot="{ navigate, isActive }"
            :to="{ name: 'register' }"
          >
            <button
              class="btn w-100"
              data-bs-dismiss="offcanvas"
              :class="isActive ? 'btn-primary' : 'btn-light-grey'"
              @click="navigate"
            >
              Sign up
            </button>
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useSecurity } from '@/state/security.js';
import { useRoute, useRouter } from 'vue-router';

const { isAuthenticated, user, logout } = useSecurity();

const route = useRoute();
const router = useRouter();

const logoutApp = () => {
  if (route?.meta?.auth === true) {
    router.push({ name: 'home' });
  }
  logout();
};
</script>
